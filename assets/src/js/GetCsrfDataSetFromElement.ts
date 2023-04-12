import CsrfDataSet from './CsrfDataSet';

const GetCsrfDataSetFromElement = (el: HTMLElement): CsrfDataSet => {
    const {
        csrfTokenNameKey,
        csrfTokenName,
        csrfTokenValueKey,
        csrfTokenValue,
    } = el.dataset as CsrfDataSet;

    return {
        csrfTokenNameKey,
        csrfTokenName,
        csrfTokenValueKey,
        csrfTokenValue,
    };
};

export default GetCsrfDataSetFromElement;
