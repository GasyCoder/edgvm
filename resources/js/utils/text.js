/**
 * Strip HTML tags and decode HTML entities from a string,
 * returning clean plain text suitable for excerpts.
 *
 * @param {string|null|undefined} text
 * @returns {string}
 */
export function cleanText(text) {
    if (!text) {
        return '';
    }

    let plain = text;

    if (typeof document !== 'undefined') {
        const el = document.createElement('div');
        el.innerHTML = text;
        plain = el.textContent || el.innerText || '';
    } else {
        plain = text.replace(/<[^>]*>/g, '');
    }

    return plain.replace(/\s+/g, ' ').trim();
}
