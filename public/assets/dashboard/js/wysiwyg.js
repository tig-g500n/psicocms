(() => {
    "use strict";

    document.addEventListener("DOMContentLoaded", () => {
        if (typeof Jodit === "undefined") {
            return;
        }

        document.querySelectorAll("textarea.js-wysiwyg").forEach((textarea) => {
            Jodit.make(textarea, {
                language: "es",
                height: 380,
                toolbarAdaptive: false,
                statusbar: false,
                placeholder: "Escribe aquí…",
                buttons: [
                    "bold", "italic", "underline", "strikethrough", "|",
                    "ul", "ol", "|",
                    "paragraph", "fontsize", "brush", "|",
                    "align", "|",
                    "link", "image", "table", "|",
                    "hr", "eraser", "|",
                    "undo", "redo", "|",
                    "source",
                ],
            });
        });
    });
})();
