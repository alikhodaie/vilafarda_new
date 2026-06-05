window.IndexSectionVisibility = {
    hide(element) {
        const section = element?.closest?.('.index-section, .last-minute-off');

        if (!section) {
            return;
        }

        const parent = section.parentElement;

        section.remove();

        if (parent?.classList?.contains('container') && parent.childElementCount === 0) {
            parent.remove();
        }
    },

    hideIfEmpty(element, items) {
        if (!items || items.length === 0) {
            this.hide(element);

            return true;
        }

        return false;
    },
};
