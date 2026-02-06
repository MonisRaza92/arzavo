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

import "alpine-turbo-drive-adapter";
window.Alpine = Alpine;
Alpine.start();

if ("serviceWorker" in navigator) {
    window.addEventListener("load", () => {
        navigator.serviceWorker.register("/sw.js");
    });
}

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
            `[data-section-id="${sectionId}"]`,
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

    if (e.target.closest(".menu, .block-editor-header, button, input, textarea, select, form")) {
        return;
    }

    const section = e.target.closest("[data-section-id]");
    const block = e.target.closest("[data-block-id]");

    if (!section && !block) return;

    e.preventDefault();
    e.stopPropagation();

    if (block) {
        window.parent.openBlockEditor(block.dataset.blockId);
    } else {
        window.parent.openSectionEditor(section.dataset.sectionId);
    }
}, true);


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
        `[data-section-id="${sectionId}"]`,
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
        `[data-section-id="${sectionId}"]`,
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

    const blockEl = document.querySelector(
        `[data-block-id="${blockId}"]`
    );
    if (!blockEl) return;

    const sectionId = blockEl.dataset.sectionId;

    // ✅ 1. Section ki block list open karo (MISSING STEP)
    openSectionBlockList(sectionId);

    // ✅ 2. Parent nested blocks open karo
    openParentNestedBlocks(blockId);

    // ✅ 3. Forms reset
    document.querySelectorAll(".edit-block-form").forEach((f) => {
        f.classList.add("hidden");
    });

    document.querySelectorAll(".section-edit-form").forEach((f) => {
        f.classList.add("hidden");
    });

    // ✅ 4. Open target block form
    const editForm = document.getElementById("edit-block-form-" + blockId);
    if (!editForm) return;

    editForm.classList.remove("hidden");
    editForm.scrollTop = 0;

    // ✅ 5. Preview highlight
    clearPreviewHighlights();

    const previewDoc =
        document.getElementById("livePreviewContent")?.contentWindow?.document;

    const previewBlock = previewDoc?.querySelector(
        `[data-block-id="${blockId}"]`
    );

    if (previewBlock) {
        previewBlock.classList.add("preview-active");
        scrollPreviewIntoView(previewBlock, "center");
    }

    window.currentOpenBlockId = blockId;
    window.currentOpenSectionId = null;
};

function openSectionBlockList(sectionId) {
    const blocks = document.getElementById("blocks-" + sectionId);
    const arrow = document.getElementById("arrow-" + sectionId);

    if (blocks && blocks.classList.contains("hidden")) {
        blocks.classList.remove("hidden");
        arrow?.classList.add("rotate-90");

        // save section state
        blocksState[sectionId] = true;
        localStorage.setItem(
            "SectionBlocksState",
            JSON.stringify(blocksState)
        );
    }
}


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
            `nested-blocks-${parentBlockId}`,
        );
        const arrow = document.getElementById(
            `block-btn-arrow-${parentBlockId}`,
        );

        if (container && container.classList.contains("hidden")) {
            container.classList.remove("hidden");
            arrow?.classList.add("rotate-90");

            // save state
            const state = JSON.parse(
                localStorage.getItem("nestedBlocksState") || "{}",
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
        `[data-block-id="${blockId}"]`,
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
        `[data-block-id="${blockId}"]`,
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
            `[data-section-id="${window.currentOpenSectionId}"]`,
        );
        sectionEl?.classList.add("preview-active");
    }

    // 🟧 BLOCK ACTIVE (block overrides section)
    if (window.currentOpenBlockId) {
        const blockEl = previewDoc.querySelector(
            `[data-block-id="${window.currentOpenBlockId}"]`,
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





document.addEventListener("turbo:load", () => {

    // Preset colors
    const presetColors = [
        '#000000', '#ffffff', '#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff',
        '#ff6b6b', '#4ecdc4', '#45b7d1', '#f7b731', '#5f27cd', '#00d2d3', '#ff9ff3'
    ];

    document.querySelectorAll("[data-color-picker]").forEach(cp => {

        const hidden = cp.querySelector("input[type=hidden]");
        let initialValue = hidden.value || "#f2f2f2";

        const preview = cp.querySelectorAll("[data-preview]");
        const popup = cp.querySelector("[data-popup]");
        const input = cp.querySelector("[data-input]");
        const trigger = cp.querySelector("[data-trigger]");

        const solidUI = cp.querySelector("[data-solid]");
        const gradientUI = cp.querySelector("[data-gradient-ui]");
        const toggle = cp.querySelector("[data-toggle]");
        const swatchesContainer = cp.querySelector("[data-swatches]");
        const presetsContainer = cp.querySelector("[data-presets]");
        const gradientBar = cp.querySelector("[data-gradient-bar]");
        const stopMarkers = cp.querySelector("[data-stop-markers]");

        let mode = "solid";
        let angle = 135;
        let currentColor = initialValue;
        let stops = [{
            color: "#ff6b6b",
            pos: 0
        },
        {
            color: "#4ecdc4",
            pos: 50
        },
        {
            color: "#45b7d1",
            pos: 100
        }
        ];

        function openPopup(e) {
            e.preventDefault();
            e.stopPropagation();
            popup.classList.remove("hidden");
        }

        trigger.addEventListener("mousedown", openPopup);
        input.addEventListener("mousedown", openPopup);


        document.addEventListener("mousedown", e => {
            if (!cp.contains(e.target)) {
                popup.classList.add("hidden");
            }
        });


        /* ---------------- CORE UPDATE ---------------- */
        function update(value) {
            hidden.value = value;
            input.value = value;
            preview.forEach(p => p.style.background = value);
        }

        /* ---------------- COLOR UTILITIES ---------------- */
        function hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }

        function rgbToHex(r, g, b) {
            return "#" + [r, g, b].map(x => {
                const hex = x.toString(16);
                return hex.length === 1 ? "0" + hex : hex;
            }).join("");
        }

        function rgbToHsl(r, g, b) {
            r /= 255;
            g /= 255;
            b /= 255;
            const max = Math.max(r, g, b),
                min = Math.min(r, g, b);
            let h, s, l = (max + min) / 2;

            if (max === min) {
                h = s = 0;
            } else {
                const d = max - min;
                s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                switch (max) {
                    case r:
                        h = ((g - b) / d + (g < b ? 6 : 0)) / 6;
                        break;
                    case g:
                        h = ((b - r) / d + 2) / 6;
                        break;
                    case b:
                        h = ((r - g) / d + 4) / 6;
                        break;
                }
            }
            return {
                h: h * 360,
                s: s * 100,
                l: l * 100
            };
        }

        function hslToRgb(h, s, l) {
            h /= 360;
            s /= 100;
            l /= 100;
            let r, g, b;

            if (s === 0) {
                r = g = b = l;
            } else {
                const hue2rgb = (p, q, t) => {
                    if (t < 0) t += 1;
                    if (t > 1) t -= 1;
                    if (t < 1 / 6) return p + (q - p) * 6 * t;
                    if (t < 1 / 2) return q;
                    if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
                    return p;
                };
                const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
                const p = 2 * l - q;
                r = hue2rgb(p, q, h + 1 / 3);
                g = hue2rgb(p, q, h);
                b = hue2rgb(p, q, h - 1 / 3);
            }
            return {
                r: Math.round(r * 255),
                g: Math.round(g * 255),
                b: Math.round(b * 255)
            };
        }

        /* ---------------- SWATCHES GENERATION ---------------- */
        function generateSwatches(baseColor) {
            const rgb = hexToRgb(baseColor);
            if (!rgb) return [];

            const hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
            const swatches = [];

            // Lightness variations
            for (let i = 0; i < 5; i++) {
                const newL = Math.max(10, Math.min(90, hsl.l + (i - 2) * 15));
                const newRgb = hslToRgb(hsl.h, hsl.s, newL);
                swatches.push(rgbToHex(newRgb.r, newRgb.g, newRgb.b));
            }

            // Saturation variations
            for (let i = 0; i < 5; i++) {
                const newS = Math.max(10, Math.min(100, hsl.s + (i - 2) * 20));
                const newRgb = hslToRgb(hsl.h, newS, hsl.l);
                swatches.push(rgbToHex(newRgb.r, newRgb.g, newRgb.b));
            }

            return swatches;
        }

        function renderSwatches(color) {
            if (!swatchesContainer) return;

            const swatches = generateSwatches(color);
            swatchesContainer.innerHTML = swatches.map(swatch => `
                    <button type="button" 
                        class="w-full aspect-square border-rounded border-primary hover:scale-110 transition-all cursor-pointer"
                        style="background: ${swatch}"
                        data-swatch="${swatch}">
                    </button>
                `).join('');

            swatchesContainer.querySelectorAll('[data-swatch]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const color = btn.dataset.swatch;
                    currentColor = color;
                    cp.querySelector('[data-solid-picker]').value = color;
                    update(color);
                    renderSwatches(color);
                });
            });
        }

        /* ---------------- PRESETS ---------------- */
        function renderPresets() {
            if (!presetsContainer) return;

            presetsContainer.innerHTML = presetColors.map(color => `
                    <button type="button" 
                        class="w-full aspect-square border-rounded border-primary hover:scale-110 transition-all cursor-pointer"
                        style="background: ${color}"
                        data-preset="${color}">
                    </button>
                `).join('');

            presetsContainer.querySelectorAll('[data-preset]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const color = btn.dataset.preset;
                    currentColor = color;
                    cp.querySelector('[data-solid-picker]').value = color;
                    update(color);
                    renderSwatches(color);
                });
            });
        }

        /* ---------------- SOLID MODE ---------------- */
        const solidPicker = cp.querySelector("[data-solid-picker]");
        if (solidPicker) {
            solidPicker.addEventListener("input", e => {
                currentColor = e.target.value;
                update(currentColor);
                renderSwatches(currentColor);
            });
        }

        input.addEventListener("input", e => {
            const val = e.target.value;
            if (/^#[0-9A-F]{6}$/i.test(val)) {
                currentColor = val;
                if (solidPicker) solidPicker.value = val;
                renderSwatches(val);
            }
            update(val);
        });

        /* ---------------- MODE TOGGLE ---------------- */
        toggle?.addEventListener("click", e => {
            const btn = e.target.closest("button");
            if (!btn) return;

            mode = btn.dataset.mode;
            solidUI.classList.toggle("hidden", mode !== "solid");
            gradientUI.classList.toggle("hidden", mode !== "gradient");

            toggle.querySelectorAll("button").forEach(b => {
                if (b === btn) {
                    b.classList.add("bg-white", "shadow-sm", "text-gray-900");
                    b.classList.remove("text-gray-600");
                } else {
                    b.classList.remove("bg-white", "shadow-sm", "text-gray-900");
                    b.classList.add("text-gray-600");
                }
            });

            if (mode === "gradient") {
                buildGradient();
            }
        });

        /* ---------------- ANGLE WHEEL ---------------- */
        const wheel = cp.querySelector("[data-angle-wheel]");
        const indicator = cp.querySelector("[data-angle-indicator]");
        const angleDisplay = cp.querySelector("[data-angle-display]");

        function updateAngle(newAngle) {
            angle = ((newAngle % 360) + 360) % 360;
            if (indicator) indicator.style.transform = `rotate(${angle}deg)`;
            if (angleDisplay) angleDisplay.textContent = `${Math.round(angle)}°`;
            buildGradient();
        }

        wheel?.addEventListener("mousedown", e => {
            e.stopPropagation();
            const moveHandler = (e) => {
                const rect = wheel.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                const newAngle = Math.round((Math.atan2(y, x) * 180) / Math.PI + 90);
                updateAngle(newAngle);
            };

            moveHandler(e);

            const upHandler = () => {
                document.removeEventListener("mousemove", moveHandler);
                document.removeEventListener("mouseup", upHandler);
            };

            document.addEventListener("mousemove", moveHandler);
            document.addEventListener("mouseup", upHandler);
        });

        /* ---------------- QUICK ANGLES ---------------- */
        cp.querySelectorAll("[data-quick-angle]").forEach(btn => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                updateAngle(parseInt(btn.dataset.quickAngle));
            });
        });

        /* ---------------- INTERACTIVE STOP MARKERS ---------------- */
        function renderStopMarkers() {
            if (!stopMarkers) return;
            stopMarkers.innerHTML = '';

            stops.forEach((stop, index) => {
                const marker = document.createElement('div');
                marker.className = 'stop-marker';
                marker.style.left = `${stop.pos}%`;
                marker.setAttribute('data-marker-index', index);
                marker.innerHTML = `<div class="stop-marker-handle" style="background: ${stop.color}"></div>`;

                // DRAG FUNCTIONALITY - PROPERLY IMPLEMENTED
                marker.addEventListener('mousedown', (e) => {
                    e.stopPropagation();
                    e.preventDefault();

                    const rect = stopMarkers.getBoundingClientRect();
                    marker.style.zIndex = '10';
                    document.body.style.cursor = 'grabbing';

                    const moveHandler = (e) => {
                        e.preventDefault();
                        const x = Math.max(0, Math.min(rect.width, e.clientX - rect.left));
                        const percentage = Math.round((x / rect.width) * 100);

                        stop.pos = percentage;
                        marker.style.left = `${percentage}%`;

                        buildGradient();

                        // Update the corresponding range slider
                        const stopRow = stopsBox.children[index];
                        if (stopRow) {
                            const rangeInput = stopRow.querySelector('input[type="range"]');
                            const posDisplay = stopRow.querySelector('.text-xs.font-bold');
                            if (rangeInput) rangeInput.value = percentage;
                            if (posDisplay) posDisplay.textContent = `${percentage}%`;
                        }
                    };

                    const upHandler = () => {
                        marker.style.zIndex = '';
                        document.body.style.cursor = '';
                        document.removeEventListener('mousemove', moveHandler);
                        document.removeEventListener('mouseup', upHandler);
                        renderStops(); // Final update
                    };

                    document.addEventListener('mousemove', moveHandler);
                    document.addEventListener('mouseup', upHandler);
                });

                stopMarkers.appendChild(marker);
            });
        }

        /* ---------------- GRADIENT STOPS ---------------- */
        const stopsBox = cp.querySelector("[data-stops]");
        let sortableInstance = null;

        function renderStops() {
            if (!stopsBox) return;

            stopsBox.innerHTML = "";

            stops.forEach((stop, i) => {
                const row = document.createElement("div");
                row.setAttribute('data-stop-index', i);
                row.className = "flex items-center gap-4 p-2 w-full bg-secondary border-rounded border-primary";

                row.innerHTML = `
                        <input type="color" value="${stop.color}"
                            class="w-12 h-12 border-2 border-white border-rounded cursor-pointer hover:scale-105 transition-all flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <input type="range" min="0" max="100" value="${stop.pos}"
                                class="w-full" style="color: ${stop.color}">
                            <div class="flex items-center justify-between mt-1 px-1">
                                <span class="text-xs text-gray-500 font-medium">Position</span>
                                <span class="text-xs font-bold text-gray-700">${stop.pos}%</span>
                            </div>
                        </div>
                        ${stops.length > 2 ? `
                        <button type="button" class="w-9 h-9 border-rounded bg-primary text-tertiary hover:bg-red-100 transition-all font-bold text-lg flex-shrink-0">
                            <i class="fa-solid fa-trash-alt"></i>
                        </button>` : '<div class="w-9 shrink-0"></div>'}
                    `;

                const dragHandle = row.querySelector('.drag-handle');
                const colorInput = row.querySelector('input[type="color"]');
                const rangeInput = row.querySelector('input[type="range"]');
                const posDisplay = row.querySelector('.text-xs.font-bold');
                const deleteBtn = row.querySelector('button');

                colorInput.oninput = (e) => {
                    e.stopPropagation();
                    stop.color = colorInput.value;
                    rangeInput.style.color = stop.color;
                    buildGradient();
                    renderStopMarkers();
                };

                rangeInput.oninput = (e) => {
                    e.stopPropagation();
                    stop.pos = parseInt(rangeInput.value);
                    posDisplay.textContent = `${stop.pos}%`;
                    buildGradient();
                    renderStopMarkers();
                };

                if (deleteBtn) {
                    deleteBtn.onclick = (e) => {
                        e.stopPropagation();
                        stops.splice(i, 1);
                        renderStops();
                        buildGradient();
                        renderStopMarkers();
                    };
                }

                stopsBox.appendChild(row);
            });

            // Destroy previous sortable instance if exists
            if (sortableInstance) {
                sortableInstance.destroy();
            }
        }

        cp.querySelector("[data-add-stop]")?.addEventListener("click", (e) => {
            e.stopPropagation();

            // Find the biggest gap between stops
            let maxGap = 0;
            let gapStart = 0;

            const sortedStops = [...stops].sort((a, b) => a.pos - b.pos);

            for (let i = 0; i < sortedStops.length - 1; i++) {
                const gap = sortedStops[i + 1].pos - sortedStops[i].pos;
                if (gap > maxGap) {
                    maxGap = gap;
                    gapStart = sortedStops[i].pos;
                }
            }

            const newPos = gapStart + Math.round(maxGap / 2);

            stops.push({
                color: "#ffffff",
                pos: newPos
            });

            renderStops();
            buildGradient();
            renderStopMarkers();
        });

        function buildGradient() {
            const sortedStops = [...stops].sort((a, b) => a.pos - b.pos);
            const css = `linear-gradient(${angle}deg, ${sortedStops.map(s => `${s.color} ${s.pos}%`).join(", ")
                })`;
            update(css);

            if (gradientBar) {
                gradientBar.style.background = `linear-gradient(90deg, ${sortedStops.map(s => `${s.color} ${s.pos}%`).join(", ")
                    })`;
            }
        }

        /* ---------------- INITIALIZATION ---------------- */
        updateAngle(angle);
        renderStops();
        renderSwatches(currentColor);
        renderPresets();
        renderStopMarkers();

        if (mode === "solid") {
            update(currentColor);
            if (solidPicker) solidPicker.value = currentColor;
        } else {
            buildGradient();
        }
    });
});