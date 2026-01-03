import "./bootstrap";
import Alpine from "alpinejs";
import "@hotwired/turbo";
import Coloris from "@melloware/coloris";
import "@melloware/coloris/dist/coloris.css";

window.Alpine = Alpine;
Alpine.start();

// expose for debug (optional)
window.Coloris = Coloris;

document.addEventListener("DOMContentLoaded", () => {
    Coloris.init({
        theme: "large",
        themeMode: "light",

        format: "auto", // 👈 VERY IMPORTANT
        formatToggle: true, // 👈 allows solid ↔ gradient
        alpha: true,

        swatches: [
            "#000000",
            "#ffffff",
            "#F44336",
            "#E91E63",
            "#9C27B0",
            "#3F51B5",
            "#2196F3",
            "#00BCD4",
            "#009688",
            "#4CAF50",
            "#FFEB3B",
            "#FF9800",
        ],

        clearButton: true,
        closeButton: true,
        closeOnScroll: true,
    });
});

// ✅ Turbo navigations → ONLY re-scan
document.addEventListener("turbo:load", () => {
    if (window.Coloris) {
        window.Coloris.init();
    }
});

function toggleModel(id) {
    const model = document.getElementById(id);
    if (!model) return;
    model.classList.toggle("hidden");
}

function openModel(id) {
    const model = document.getElementById(id);
    if (!model) return;
    model.classList.remove("hidden");
}

function closeModel(id) {
    const model = document.getElementById(id);
    if (!model) return;
    model.classList.add("hidden");
}

window.toggleModel = toggleModel;
window.openModel = openModel;
window.closeModel = closeModel;

window.openSectionEditor = function (sectionId) {
    openEditorTab("sections");

    // 🔴 FORCE CLOSE ALL BLOCKS (HARD RESET)
    document.querySelectorAll(".edit-block-form").forEach((f) => {
        f.classList.add("hidden");
    });
    window.currentOpenSectionId = sectionId;
    window.currentOpenBlockId = null;

    // 🔴 CLEAR ALL PREVIEW HIGHLIGHTS
    clearPreviewHighlights();

    // 🔴 CLOSE ALL SECTION FORMS
    document.querySelectorAll(".section-edit-form").forEach((f) => {
        f.classList.add("hidden");
    });
    // 🔹 1. Sab section edit forms band karo
    document.querySelectorAll(".section-edit-form").forEach((form) => {
        form.classList.add("hidden");
    });

    // 🔹 2. Sab preview highlights hatao
    document
        .getElementById("livePreviewContent")
        ?.contentWindow?.document?.querySelectorAll(".preview-active")
        ?.forEach((el) => el.classList.remove("preview-active"));

    // 🔹 3. Target section ka edit form open karo
    const editForm = document.getElementById("edit-form-" + sectionId);
    if (!editForm) return;

    editForm.classList.remove("hidden");

    // 🔹 4. Blocks open + arrow rotate (same as header click)
    const blocks = document.getElementById("blocks-" + sectionId);
    const arrow = document.getElementById("arrow-" + sectionId);

    blocks?.classList.remove("hidden");
    arrow?.classList.add("rotate-90");

    // 🔹 5. Preview me highlight add karo
    const previewSection = document
        .getElementById("livePreviewContent")
        ?.contentWindow?.document?.querySelector(
            `[data-section-id="${sectionId}"]`
        );

    if (previewSection) {
        previewSection.classList.add("preview-active");
        scrollPreviewIntoView(previewSection, "center"); // 🔥 ADD THIS
    }

    // 🔹 6. Save blocks state
    blocksState[sectionId] = true;
    localStorage.setItem("SectionBlocksState", JSON.stringify(blocksState));
};

document.addEventListener("click", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    // ✅ IGNORE form elements (THIS IS THE FIX)
    if (e.target.closest("input, textarea, select, label, button")) {
        return;
    }

    const section = e.target.closest("[data-section-id]");
    if (!section) return;

    e.preventDefault();
    e.stopPropagation();

    const id = section.dataset.sectionId;
    window.parent.openSectionEditor(id);
});

document.addEventListener("mouseover", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    const header = e.target.closest(".section-items");
    if (!header) return;

    const li = header.closest("li[data-id]");
    if (!li) return;

    const sectionId = li.dataset.id;

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewSection = previewDoc?.querySelector(
        `[data-section-id="${sectionId}"]`
    );

    if (!previewSection) return;

    // same class
    previewSection.classList.add("preview-hover");
    scrollPreviewIntoView(previewSection, "nearest");
});
document.addEventListener("mouseout", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    const header = e.target.closest(".section-items");
    if (!header) return;

    const li = header.closest("li[data-id]");
    if (!li) return;

    const sectionId = li.dataset.id;

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewSection = previewDoc?.querySelector(
        `[data-section-id="${sectionId}"]`
    );

    if (!previewSection) return;

    // same class
    previewSection.classList.remove("preview-hover");
});

// Block State Management
let blocksState = {};
const savedState = localStorage.getItem("SectionBlocksState");
if (savedState) {
    blocksState = JSON.parse(savedState);
}

window.openBlockEditor = function (blockId) {
    openEditorTab("sections");

    // close sections
    document.querySelectorAll(".section-edit-form").forEach((f) => {
        f.classList.add("hidden");
    });
    window.currentOpenBlockId = blockId;
    window.currentOpenSectionId = null;

    // close blocks
    document.querySelectorAll(".edit-block-form").forEach((f) => {
        f.classList.add("hidden");
    });

    // clear preview
    clearPreviewHighlights();

    const editForm = document.getElementById("edit-block-form-" + blockId);
    if (!editForm) return;

    editForm.classList.remove("hidden");
    editForm.scrollTop = 0;

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewBlock = previewDoc?.querySelector(
        `[data-block-id="${blockId}"]`
    );

    if (previewBlock) {
        previewBlock.classList.add("preview-active");
        scrollPreviewIntoView(previewBlock, "center"); // 🔥
    }

    window.currentOpenBlockId = blockId;
};

document.addEventListener("click", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    // ✅ IGNORE form elements inside block editor
    if (e.target.closest("input, textarea, select, label, button")) {
        return;
    }

    // ❌ Ignore explicit close button
    if (e.target.closest("#blockFormClose")) {
        return;
    }

    const block = e.target.closest("[data-block-id]");
    if (!block) return;

    e.preventDefault();
    e.stopPropagation();

    const id = block.dataset.blockId;
    window.parent.openBlockEditor(id);
});

document.addEventListener("mouseover", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    const blockEl = e.target.closest(".block-item");
    if (!blockEl) return;

    const blockId = blockEl.dataset.blockId;

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewBlock = previewDoc?.querySelector(
        `[data-block-id="${blockId}"]`
    );

    if (!previewBlock) return;

    // same class
    previewBlock.classList.add("preview-hover");
    scrollPreviewIntoView(previewBlock, "nearest");
});
document.addEventListener("mouseout", function (e) {
    if (!window.ARZAVO_EDITOR_MODE) return;
    const blockEl = e.target.closest(".block-item");
    if (!blockEl) return;

    const blockId = blockEl.dataset.blockId;

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewBlock = previewDoc?.querySelector(
        `[data-block-id="${blockId}"]`
    );

    if (!previewBlock) return;

    // same class
    previewBlock.classList.remove("preview-hover");
});

function clearPreviewHighlights() {
    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    if (!previewDoc) return;

    previewDoc
        .querySelectorAll(".preview-active")
        .forEach((el) => el.classList.remove("preview-active"));
}
window.clearPreviewHighlights = clearPreviewHighlights;

function reapplyPreviewSelection() {
    const iframe = document.getElementById("livePreviewContent");
    if (!iframe) return;

    const previewDoc = iframe.contentWindow?.document;
    if (!previewDoc) return;

    // 🟦 Clear old states
    previewDoc.querySelectorAll(".preview-active").forEach((el) => {
        el.classList.remove("preview-active");
    });

    // 🟩 SECTION ACTIVE
    if (window.currentOpenSectionId) {
        const sectionEl = previewDoc.querySelector(
            `[data-section-id="${window.currentOpenSectionId}"]`
        );
        sectionEl?.classList.add("preview-active");
    }

    // 🟧 BLOCK ACTIVE (block overrides section)
    if (window.currentOpenBlockId) {
        const blockEl = previewDoc.querySelector(
            `[data-block-id="${window.currentOpenBlockId}"]`
        );
        blockEl?.classList.add("preview-active");
    }
}
window.reapplyPreviewSelection = reapplyPreviewSelection;

(function attachPreviewReloadHandler() {
    const iframe = document.getElementById("livePreviewContent");
    if (!iframe) return;

    // prevent multiple bindings
    if (iframe.dataset.listenerAttached === "1") return;
    iframe.dataset.listenerAttached = "1";

    iframe.addEventListener("load", () => {
        // DOM settle hone do
        setTimeout(() => {
            window.reapplyPreviewSelection();
        }, 50);
    });
})();

function scrollPreviewIntoView(element, mode = "center") {
    if (!element) return;

    element.scrollIntoView({
        behavior: "smooth",
        block: mode === "center" ? "center" : "nearest",
        inline: "nearest",
    });
}
window.scrollPreviewIntoView = scrollPreviewIntoView;

document.addEventListener("turbo:click", (e) => {
    const btn = e.target.closest("[data-loading]");
    if (!btn) return;

    // store full HTML once
    if (!btn.dataset.originalHtml) {
        btn.dataset.originalHtml = btn.innerHTML;
    }

    btn.classList.add("opacity-80", "pointer-events-none");
    btn.innerHTML = "Loading...";
});

document.addEventListener("turbo:before-cache", () => {
    document.querySelectorAll("[data-loading]").forEach((btn) => {
        if (btn.dataset.originalHtml) {
            btn.innerHTML = btn.dataset.originalHtml;
        }

        btn.classList.remove("opacity-80", "pointer-events-none");
    });
});
