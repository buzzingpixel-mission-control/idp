import { FormValues } from './FormValues';
import FetchOptionsBuilder from '../Utility/FetchOptionsBuilder';
import CsrfDataSet from '../CsrfDataSet';

export default async (
    formValues: FormValues,
    csrfData: CsrfDataSet,
) => {
    try {
        formValues[csrfData.csrfTokenNameKey] = csrfData.csrfTokenName;

        formValues[csrfData.csrfTokenValueKey] = csrfData.csrfTokenValue;

        await fetch(
            '/password-reset',
            FetchOptionsBuilder(formValues),
        );

        return true;
    } catch (error) {
        return Promise.reject(error);
    }
};
