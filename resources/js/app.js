import "./bootstrap";
import Alpine from "alpinejs";
import "@hotwired/turbo";
import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import { TextStyle } from "@tiptap/extension-text-style";
import { Color } from "@tiptap/extension-color";
import { Link } from "@tiptap/extension-link";

function initRichText() {
    document.querySelectorAll(".tiptap-editor").forEach((el) => {
        if (el.editor) return;

        const hidden = el.parentElement.querySelector("input[type=hidden]");
        const content = el.dataset.content || "<p></p>";

        const editor = new Editor({
            element: el,
            content,
            extensions: [
                StarterKit.configure({
                    link: false, // disable default link
                }),
                TextStyle,
                Color,
                Link.configure({
                    openOnClick: false,
                }),
            ],
            onUpdate({ editor }) {
                hidden.value = editor.getHTML();

                hidden.dispatchEvent(new Event("input", { bubbles: true }));
                hidden.dispatchEvent(new Event("change", { bubbles: true }));
            },
        });

        el.editor = editor;

        const wrapper = el.closest(".richtext-wrapper");

        wrapper.querySelectorAll("[data-action]").forEach((btn) => {
            btn.onclick = () => {
                const action = btn.dataset.action;

                if (action === "bold")
                    editor.chain().focus().toggleBold().run();
                if (action === "italic")
                    editor.chain().focus().toggleItalic().run();
                if (action === "underline")
                    editor.chain().focus().toggleUnderline().run();

                if (action === "link") {
                    linkBar.classList.toggle("hidden");
                    linkInput.focus();
                }
            };
        });

        wrapper.querySelector("input[type=color]").oninput = (e) => {
            editor.chain().focus().setColor(e.target.value).run();
        };

        const linkBar = wrapper.querySelector(".link-bar");
        const linkInput = wrapper.querySelector(".link-url");
        const linkApply = wrapper.querySelector(".link-apply");
        const linkRemove = wrapper.querySelector(".link-remove");

        if (!linkBar) return;

        // Detect cursor change
        editor.on("selectionUpdate", () => {
            const href = editor.getAttributes("link").href;
            if (href) {
                linkBar.classList.remove("hidden");
                linkInput.value = href;
            } else {
                linkBar.classList.add("hidden");
                linkInput.value = "";
            }
        });

        // Apply link
        linkApply.onclick = () => {
            const url = linkInput.value;
            if (url) {
                editor.chain().focus().setLink({ href: url }).run();
            }
        };

        // Remove link
        linkRemove.onclick = () => {
            editor.chain().focus().unsetLink().run();
            linkBar.classList.add("hidden");
        };
    });
}

document.addEventListener("turbo:load", () => {
    setTimeout(initRichText, 50);
});

document.addEventListener("turbo:before-cache", () => {
    document.querySelectorAll(".tiptap-editor").forEach((el) => {
        if (el.editor) {
            el.editor.destroy();
            el.editor = null;
        }
    });
});

window.Alpine = Alpine;
Alpine.start();

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

    openParentNestedBlocks(blockId);
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

function openParentNestedBlocks(blockId) {
    let current = document.getElementById(`block-${blockId}`);
    if (!current) return;

    // 🔁 walk up DOM tree
    while (current) {
        // check if we are inside a nested-blocks container
        const nestedContainer = current.closest("div[id^='nested-blocks-']");
        if (!nestedContainer) break;

        const parentBlockLi = nestedContainer.closest("li.block-item");
        if (!parentBlockLi) break;

        const parentBlockId = parentBlockLi.dataset.blockId;

        const container = document.getElementById(
            `nested-blocks-${parentBlockId}`
        );
        const arrow = document.getElementById(
            `block-btn-arrow-${parentBlockId}`
        );

        if (container && container.classList.contains("hidden")) {
            container.classList.remove("hidden");
            arrow?.classList.add("rotate-90");

            // save state
            const state = JSON.parse(
                localStorage.getItem("nestedBlocksState") || "{}"
            );
            state[parentBlockId] = true;
            localStorage.setItem("nestedBlocksState", JSON.stringify(state));
        }

        // move one level up
        current = parentBlockLi;
    }
}

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

class GradientPicker {
    constructor(targetInput, options = {}) {
        this.targetInput = targetInput;
        this.options = {
            defaultAngle: 90,
            defaultColor: "#4f46e5",
            presets: [
                ["#4f46e5", "#9333ea"],
                ["#3b82f6", "#06b6d4"],
                ["#f59e0b", "#ef4444"],
                ["#10b981", "#3b82f6"],
                ["#ec4899", "#8b5cf6"],
                ["#222222", "#000000"],
            ],
            ...options,
        };

        this.state = {
            mode: "gradient",
            angle: this.options.defaultAngle,
            stops: [
                { color: "#4f46e5", position: 0 },
                { color: "#9333ea", position: 100 },
            ],
            solidColor: this.options.defaultColor,
        };

        this.isOpen = false;
        this.popup = null;
        this.init();
    }

    init() {
        this.injectStyles();

        // Parse initial value
        if (this.targetInput.value) {
            this.parseInput(this.targetInput.value);
        }

        // --- 1. VISUAL FIX: Input Styling ---
        // Swatch element remove kar diya hai.
        // Direct input ko style kar rahe hain taaki wo color box jaisa dikhe.
        this.targetInput.classList.add("pgp-input-visual");
        this.targetInput.readOnly = true;
        this.updateInputVisual();

        // --- Events ---
        const toggleFn = (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggle();
        };

        this.targetInput.addEventListener("click", toggleFn);

        // Global Click to Close
        document.addEventListener("click", (e) => {
            if (
                this.isOpen &&
                this.popup &&
                !this.popup.contains(e.target) &&
                e.target !== this.targetInput
            ) {
                this.close();
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && this.isOpen) this.close();
        });
    }

    injectStyles() {
        if (document.getElementById("pgp-styles")) return;
        const style = document.createElement("style");
        style.id = "pgp-styles";
        style.textContent = `
            /* --- INPUT STYLING (The w-32 colored box) --- */
            .pgp-input-visual {
                /* Text Hide Logic */
                color: transparent !important;
                text-shadow: none !important;
                caret-color: transparent;
                cursor: pointer;
                
                /* Box Styling */
                width: 127px !important; /* Approx w-32 */
                height: 38px !important;
                background-size: cover;
                background-position: center;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .pgp-input-visual:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            }

            /* --- POPUP STYLING --- */
            .pgp-popup {
                position: fixed; 
                z-index: 99999; 
                width: 300px;
                background: #fff; 
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                border: 1px solid #e5e7eb; 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                opacity: 0; 
                transition: opacity 0.1s ease;
                display: flex;
                flex-direction: column;
            }
            .pgp-popup.visible { opacity: 1; }
            
            /* Tabs */
            .pgp-tabs { display: flex; border-bottom: 1px solid #e5e7eb; padding: 4px 4px 0; background: #f9fafb; border-radius: 12px 12px 0 0; }
            .pgp-tab {
                flex: 1; padding: 10px; font-size: 13px; font-weight: 600; color: #6b7280;
                background: transparent; border: none; cursor: pointer; border-bottom: 2px solid transparent;
            }
            .pgp-tab.active { color: #4f46e5; border-bottom-color: #4f46e5; }
            
            /* Content */
            .pgp-content { padding: 16px; overflow-y: auto; max-height: 380px; }
            
            /* Preview */
            .pgp-preview {
                height: 80px; width: 100%; border-radius: 8px; margin-bottom: 16px;
                border: 1px solid #e5e7eb; position: relative; overflow: hidden;
                /* Checkerboard background for transparency reference */
                background-image: linear-gradient(45deg, #ccc 25%, transparent 25%), linear-gradient(-45deg, #ccc 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #ccc 75%), linear-gradient(-45deg, transparent 75%, #ccc 75%);
                background-size: 10px 10px;
            }
            .pgp-preview-inner { position: absolute; inset: 0; }
            
            /* Controls */
            .pgp-row { margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; }
            .pgp-label { font-size: 12px; font-weight: 600; color: #374151; }
            
            /* Sliders & Inputs */
            input[type=range].pgp-slider { -webkit-appearance: none; width: 100%; height: 6px; background: #e5e7eb; border-radius: 3px; outline: none; }
            input[type=range].pgp-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 16px; height: 16px; border-radius: 50%; background: #4f46e5; cursor: pointer; border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
            
            /* Stops */
            .pgp-stops { max-height: 120px; overflow-y: auto; margin: 0 -8px; padding: 0 8px; }
            .pgp-stop-item { display: flex; align-items: center; gap: 8px; background: #f9fafb; padding: 6px; border-radius: 6px; margin-bottom: 6px; border: 1px solid #f3f4f6; }
            .pgp-color-input { width: 32px; height: 32px; padding: 0; border: none; border-radius: 4px; overflow: hidden; cursor: pointer; }
            .pgp-color-input::-webkit-color-swatch-wrapper { padding: 0; }
            .pgp-color-input::-webkit-color-swatch { border: none; }
            
            /* Buttons */
            .pgp-btn-icon { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border: none; background: #fee2e2; color: #ef4444; border-radius: 4px; cursor: pointer; }
            .pgp-btn-add { width: 100%; padding: 8px; background: #eff6ff; color: #4f46e5; border: 1px dashed #c7d2fe; border-radius: 6px; cursor: pointer; font-size: 12px; margin-top: 8px; }
            
            /* Presets */
            .pgp-presets { display: grid; grid-template-columns: repeat(6, 1fr); gap: 6px; margin-bottom: 12px; }
            .pgp-preset { width: 100%; aspect-ratio: 1; border-radius: 4px; border: 1px solid rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.1s; }
            .pgp-preset:hover { transform: scale(1.1); }
            
            /* Footer */
            .pgp-footer { padding: 12px 16px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 8px; background: #f9fafb; border-radius: 0 0 12px 12px; }
            .pgp-btn { padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; border: 1px solid transparent; }
            .pgp-btn-secondary { background: #fff; border-color: #d1d5db; color: #374151; }
            .pgp-btn-primary { background: #111827; color: #fff; }
        `;
        document.head.appendChild(style);
    }

    createPopup() {
        this.popup = document.createElement("div");
        this.popup.className = "pgp-popup";
        this.popup.addEventListener("click", (e) => e.stopPropagation());
        document.body.appendChild(this.popup);
        this.render();
    }

    render() {
        if (!this.popup) return;

        let html = `
            <div class="pgp-tabs">
                <button class="pgp-tab ${
                    this.state.mode === "gradient" ? "active" : ""
                }" data-tab="gradient">Gradient</button>
                <button class="pgp-tab ${
                    this.state.mode === "solid" ? "active" : ""
                }" data-tab="solid">Solid Color</button>
            </div>
            <div class="pgp-content">
        `;

        const currentStyle = this.getGradientString();
        html += `<div class="pgp-preview"><div class="pgp-preview-inner" style="background: ${currentStyle}"></div></div>`;

        html += `<div class="pgp-presets">`;
        this.options.presets.forEach((p, i) => {
            const bg = Array.isArray(p)
                ? `linear-gradient(135deg, ${p[0]}, ${p[1]})`
                : p;
            html += `<div class="pgp-preset" style="background: ${bg}" data-preset="${i}"></div>`;
        });
        html += `</div>`;

        if (this.state.mode === "gradient") {
            html += `
                <div class="pgp-row">
                    <span class="pgp-label">Angle: ${this.state.angle}°</span>
                    <input type="range" class="pgp-slider" min="0" max="360" value="${this.state.angle}" data-action="angle">
                </div>
                <div class="pgp-label" style="margin-bottom:8px">Color Stops</div>
                <div class="pgp-stops"></div>
                <button class="pgp-btn-add">+ Add Stop</button>
            `;
        } else {
            html += `
                <div class="pgp-row" style="margin-top: 16px;">
                    <span class="pgp-label">Select Color</span>
                    <input type="color" value="${this.hexToRgbInput(
                        this.state.solidColor
                    )}" data-action="solid-input" style="width: 100%; height: 40px; cursor: pointer;">
                </div>
                <div class="pgp-row">
                    <span class="pgp-label">Hex Code</span>
                    <input type="text" value="${
                        this.state.solidColor
                    }" data-action="solid-text" style="width: 80px; padding: 4px; border: 1px solid #d1d5db; border-radius: 4px; text-transform: uppercase;">
                </div>
            `;
        }
        html += `</div>
            <div class="pgp-footer">
                <button class="pgp-btn pgp-btn-secondary" data-action="cancel">Cancel</button>
                <button class="pgp-btn pgp-btn-primary" data-action="apply">Apply</button>
            </div>`;

        this.popup.innerHTML = html;

        if (this.state.mode === "gradient") this.renderStopsList();
        this.bindEvents();
    }

    renderStopsList() {
        const container = this.popup.querySelector(".pgp-stops");
        if (!container) return;
        container.innerHTML = "";
        this.state.stops.forEach((stop, index) => {
            const div = document.createElement("div");
            div.className = "pgp-stop-item";
            div.innerHTML = `
                <input type="color" class="pgp-color-input" value="${this.hexToRgbInput(
                    stop.color
                )}" data-index="${index}">
                <input type="range" class="pgp-slider" min="0" max="100" value="${
                    stop.position
                }" data-index="${index}" style="flex:1">
                <span style="font-size:11px; width:28px; text-align:right">${
                    stop.position
                }%</span>
                ${
                    this.state.stops.length > 2
                        ? `<button class="pgp-btn-icon" data-action="remove" data-index="${index}">×</button>`
                        : ""
                }
            `;
            div.querySelectorAll("input").forEach((inp) =>
                inp.addEventListener("click", (e) => e.stopPropagation())
            );
            container.appendChild(div);
        });
    }

    bindEvents() {
        this.popup.querySelectorAll(".pgp-tab").forEach((btn) => {
            btn.addEventListener("click", () => {
                this.state.mode = btn.dataset.tab;
                this.render();
            });
        });

        this.popup.addEventListener("input", (e) => {
            const target = e.target;
            const action = target.dataset.action;
            const index = target.dataset.index;

            if (action === "angle") {
                this.state.angle = parseInt(target.value);
                this.popup.querySelector(
                    ".pgp-label"
                ).textContent = `Angle: ${this.state.angle}°`;
            } else if (action === "solid-input") {
                this.state.solidColor = target.value;
                const textInput = this.popup.querySelector(
                    '[data-action="solid-text"]'
                );
                if (textInput) textInput.value = target.value;
            } else if (index !== undefined) {
                if (target.type === "color")
                    this.state.stops[index].color = target.value;
                if (target.type === "range") {
                    this.state.stops[index].position = parseInt(target.value);
                    target.nextElementSibling.textContent = target.value + "%";
                }
            }
            this.updateInternalPreview();
        });

        this.popup.addEventListener("click", (e) => {
            const target = e.target;
            const action = target.dataset.action;

            if (action === "remove") {
                this.state.stops.splice(parseInt(target.dataset.index), 1);
                this.render();
            } else if (target.classList.contains("pgp-btn-add")) {
                const lastPos =
                    this.state.stops[this.state.stops.length - 1].position;
                this.state.stops.push({
                    color: "#888888",
                    position: Math.min(lastPos + 20, 100),
                });
                this.render();
            } else if (target.classList.contains("pgp-preset")) {
                const preset = this.options.presets[target.dataset.preset];
                if (Array.isArray(preset)) {
                    this.state.mode = "gradient";
                    this.state.stops = [
                        { color: preset[0], position: 0 },
                        { color: preset[1], position: 100 },
                    ];
                } else {
                    this.state.mode = "solid";
                    this.state.solidColor = preset;
                }
                this.render();
            } else if (action === "cancel") {
                this.close();
            } else if (action === "apply") {
                this.apply();
            }
        });

        const solidText = this.popup.querySelector(
            '[data-action="solid-text"]'
        );
        if (solidText) {
            solidText.addEventListener("change", (e) => {
                this.state.solidColor = e.target.value;
                this.render();
            });
        }
    }

    updateInternalPreview() {
        const preview = this.popup.querySelector(".pgp-preview-inner");
        if (preview) preview.style.background = this.getGradientString();
    }

    getGradientString() {
        if (this.state.mode === "solid") return this.state.solidColor;
        const sorted = [...this.state.stops].sort(
            (a, b) => a.position - b.position
        );
        return `linear-gradient(${this.state.angle}deg, ${sorted
            .map((s) => `${s.color} ${s.position}%`)
            .join(", ")})`;
    }

    parseInput(value) {
        if (!value) return;
        if (value.includes("gradient")) {
            this.state.mode = "gradient";
            const gradientMatch = value.match(
                /linear-gradient\(([^,]+)deg,\s*(.+)\)/
            );
            if (gradientMatch) {
                this.state.angle = parseInt(gradientMatch[1]) || 90;
                const parts = gradientMatch[2]
                    .split(/,(?![^(]*\))/)
                    .map((s) => s.trim());
                this.state.stops = parts.map((part, i) => {
                    const match = part.match(/(.+?)\s+(\d+)%?/);
                    return match
                        ? { color: match[1], position: parseInt(match[2]) }
                        : {
                              color: part,
                              position: (i / (parts.length - 1)) * 100,
                          };
                });
            }
        } else {
            this.state.mode = "solid";
            this.state.solidColor = value;
        }
    }

    toggle() {
        this.isOpen ? this.close() : this.open();
    }

    open() {
        document.querySelectorAll(".pgp-popup").forEach((p) => p.remove());
        if (!this.popup) this.createPopup();

        // --- 2. SMART POSITIONING FIX ---

        // Step A: Calculate Dimensions
        const rect = this.targetInput.getBoundingClientRect();
        const popupHeight = 460; // Max estimated height
        const viewportHeight = window.innerHeight;

        const spaceBelow = viewportHeight - rect.bottom;
        const spaceAbove = rect.top;

        // Step B: Decision Logic
        let openUpwards = false;

        if (spaceBelow >= popupHeight) {
            // Case 1: Enough space below -> Open Down
            openUpwards = false;
        } else if (spaceAbove >= popupHeight) {
            // Case 2: Not enough below, but enough above -> Open Up
            openUpwards = true;
        } else {
            // Case 3: Tight fit! Neither side has full space.
            // Pick the side with MORE space.
            openUpwards = spaceAbove > spaceBelow;

            // Optional: You can force a max-height on the popup content here if needed
            // this.popup.style.maxHeight = (openUpwards ? spaceAbove : spaceBelow) - 20 + 'px';
        }

        // Step C: Apply Position
        if (openUpwards) {
            this.popup.style.top = rect.top - popupHeight - 8 + "px";
            this.popup.style.transformOrigin = "bottom left";

            // Extra Safety: Don't go off top of screen
            if (parseInt(this.popup.style.top) < 0) {
                this.popup.style.top = "10px";
            }
        } else {
            this.popup.style.top = rect.bottom + 8 + "px";
            this.popup.style.transformOrigin = "top left";
        }

        // Horizontal Safety
        if (rect.left + 300 > window.innerWidth) {
            this.popup.style.left = window.innerWidth - 310 + "px";
        } else {
            this.popup.style.left = rect.left + "px";
        }

        // Re-parse current value
        if (this.targetInput.value) this.parseInput(this.targetInput.value);

        this.render();
        requestAnimationFrame(() => {
            this.popup.classList.add("visible");
            this.isOpen = true;
        });
    }

    close() {
        if (this.popup) {
            this.popup.classList.remove("visible");
            setTimeout(() => {
                if (this.popup) this.popup.remove();
                this.popup = null;
            }, 100);
        }
        this.isOpen = false;
    }

    apply() {
        this.targetInput.value = this.getGradientString();
        this.updateInputVisual();
        this.targetInput.dispatchEvent(new Event("input", { bubbles: true }));
        this.targetInput.dispatchEvent(new Event("change", { bubbles: true }));
        this.close();
    }

    updateInputVisual() {
        // --- 3. APPLY GRADIENT TO INPUT BACKGROUND ---
        this.targetInput.style.background = this.targetInput.value;
        // Text is hidden via CSS (.pgp-input-visual)
    }

    hexToRgbInput(color) {
        if (!color || !color.startsWith("#")) return "#000000";
        if (color.length === 7) return color;
        if (color.length === 4)
            return (
                "#" +
                color[1] +
                color[1] +
                color[2] +
                color[2] +
                color[3] +
                color[3]
            );
        return color;
    }
}

// Initializer
function initGradientPickers() {
    document.querySelectorAll("[data-gradient-picker]").forEach((input) => {
        if (!input._pgp) input._pgp = new GradientPicker(input);
    });
}

if (typeof document !== "undefined") {
    document.addEventListener("DOMContentLoaded", initGradientPickers);
    document.addEventListener("turbo:load", initGradientPickers);
}
// Expose globally
window.GradientPicker = GradientPicker;
window.initGradientPickers = initGradientPickers;
