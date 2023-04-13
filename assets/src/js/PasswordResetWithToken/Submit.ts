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

        if (!formValues.password) {
            return Promise.reject(
                new Error('You must provide a password'),
            );
        }

        if (formValues.password !== formValues.passwordConfirm) {
            return Promise.reject(
                new Error('Password must match confirmation'),
            );
        }

        const response = await fetch(
            window.location.href,
            FetchOptionsBuilder(formValues),
        );

        const json = await response.json();

        if (json.success) {
            return true;
        }

        return Promise.reject(
            new Error(json.message || 'An unknown error occurred'),
        );
    } catch (error) {
        return Promise.reject(
            new Error('Unable to reset your password'),
        );
    }
};

export default Submit;
