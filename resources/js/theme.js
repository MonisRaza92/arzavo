


document.querySelectorAll('[data-block-id]').forEach(el => {

    const id = el.dataset.blockId;

    // 🔥 auto add core class
    // el.classList.add('arz-core');

    // 🔥 auto add unique class
    if (id) {
        el.classList.add('arz-' + id);
    }

});