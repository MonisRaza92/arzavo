<iframe id="livePreviewContent"
    src="{{ route('website.preview', ['slug' => $page->slug, 'theme' => $theme->theme_slug, 'theme_id' => $theme->id]) }}"
    class="border-rounded w-full h-[calc(100dvh-6rem)] border-primary z-10">
</iframe>
<script>

    (function() {
        const iframe = document.getElementById("livePreviewContent");
        if (iframe) {
            iframe.addEventListener("load", () => {
                iframe.contentWindow.postMessage(
                    {
                        type: "ARZAVO_EDITOR_MODE",
                        enabled: true
                    },
                    "*"
                );
            });
        }
    })();

    

</script>