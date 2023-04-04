import { FormValues } from './FormValues';
import FetchOptionsBuilder from '../Utility/FetchOptionsBuilder';

export default async (formValues: FormValues) => {
    try {
        await fetch(
            '/password-reset',
            FetchOptionsBuilder(formValues),
        );

        return true;
    } catch (error) {
        return Promise.reject(error);
    }
};
