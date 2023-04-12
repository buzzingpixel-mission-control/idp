import React, { useState } from 'react';
import { useForm, SubmitHandler } from 'react-hook-form';
import { XCircleIcon } from '@heroicons/react/20/solid';
import { FormValues } from './FormValues';
import Submit from './Submit';
import GetCsrfDataSetFromElement from '../GetCsrfDataSetFromElement';

const LogInPage = ({ reactContainer }: { reactContainer: HTMLDivElement }) => {
    const {
        register, handleSubmit, formState: { errors },
    } = useForm<FormValues>();

    const [submitting, setSubmitting] = useState<boolean>(false);

    const [errorMessage, setErrorMessage] = useState<string>('');

    const onSubmit: SubmitHandler<FormValues> = (data) => {
        setSubmitting(true);

        if (errorMessage) {
            setErrorMessage('');
        }

        Submit(data, GetCsrfDataSetFromElement(reactContainer))
            .then(() => {
                window.location.reload();
            })
            .catch((error) => {
                setErrorMessage(error.message);

                setSubmitting(false);
            });
    };

    reactContainer.className += ' h-full';

    const commonInputClasses = 'block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none sm:text-sm';

    const standardInputClasses = 'border-gray-300 focus:border-cyan-500 focus:ring-cyan-500';

    const errorInputClasses = 'border-red-600 focus:border-red-800 focus:ring-red-800';

    return (
        <>
            <div className="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
                <div className="sm:mx-auto sm:w-full sm:max-w-md">
                    <h2 className="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                        Log in to Mission Control
                    </h2>
                </div>

                <div className="mt-2 sm:mx-auto sm:w-full sm:max-w-md">

                    <div className={errorMessage ? 'opacity-100' : 'opacity-0'}>
                        <div className="rounded-md bg-red-50 p-4 mb-2">
                            <div className="flex">
                                <div className="flex-shrink-0">
                                    <XCircleIcon className="h-5 w-5 text-red-400" aria-hidden="true" />
                                </div>
                                <div className="ml-3 flex-1 md:flex md:justify-between">
                                    <p className="text-sm text-red-800">{errorMessage}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                        <form
                            className="space-y-6"
                            onSubmit={handleSubmit(onSubmit)}
                        >
                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                    Email address
                                </label>
                                <div className="mt-1">
                                    <input
                                        {...register('email', {
                                            required: true,
                                            pattern: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                                        })}
                                        id="email"
                                        type="email"
                                        autoComplete="email"
                                        required
                                        className={`${commonInputClasses} ${errors.email ? errorInputClasses : standardInputClasses}`}
                                        disabled={submitting}
                                    />
                                </div>
                            </div>

                            <div>
                                <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                                    Password
                                </label>
                                <div className="mt-1">
                                    <input
                                        {...register('password', {
                                            required: true,
                                        })}
                                        id="password"
                                        type="password"
                                        autoComplete="current-password"
                                        required
                                        className={`${commonInputClasses} ${errors.password ? errorInputClasses : standardInputClasses}`}
                                        disabled={submitting}
                                    />
                                </div>
                            </div>

                            <div className="text-sm text-center">
                                <a href="/password-reset" className="font-medium text-cyan-600 hover:text-cyan-500">
                                    Reset Your Password
                                </a>
                            </div>

                            <div>
                                <button
                                    type="submit"
                                    className="flex w-full justify-center rounded-md border border-transparent bg-cyan-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                                    disabled={submitting}
                                >
                                    Log In
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </>
    );
};

export default LogInPage;
