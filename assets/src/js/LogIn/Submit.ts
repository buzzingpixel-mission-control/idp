import { FormValues } from './FormValues';
import FetchOptionsBuilder from '../Utility/FetchOptionsBuilder';
import CsrfDataSet from '../CsrfDataSet';

const Submit = async (
    formValues: FormValues,
    csrfData: CsrfDataSet,
): Promise<boolean> => {
    try {
        formValues[csrfData.csrfTokenNameKey] = csrfData.csrfTokenName;

        formValues[csrfData.csrfTokenValueKey] = csrfData.csrfTokenValue;

        const response = await fetch(
            '/log-in',
            FetchOptionsBuilder(formValues),
        );

        const json = await response.json();

        if (json.loggedIn) {
            return true;
        }

        return Promise.reject(new Error(
            json.message || json.error.message,
        ));
    } catch (error) {
        return Promise.reject(
            new Error('Unable to log in with those credentials'),
        );
    }
};

export default Submit;
