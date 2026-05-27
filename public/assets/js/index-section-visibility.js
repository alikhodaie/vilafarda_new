window.IndexSectionVisibility = {
    hide(element) {
        const section = element?.closest?.('.index-section');

        if (section) {
            section.remove();
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
