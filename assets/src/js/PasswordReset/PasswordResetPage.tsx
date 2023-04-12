import { SubmitHandler, useForm } from 'react-hook-form';
import React, { useState } from 'react';
import { CheckCircleIcon, ArrowLeftIcon } from '@heroicons/react/20/solid';
import { FormValues } from './FormValues';
import Submit from './Submit';
import GetCsrfDataSetFromElement from '../GetCsrfDataSetFromElement';

const PasswordResetPage = (
    { reactContainer }: { reactContainer: HTMLDivElement },
) => {
    reactContainer.className += ' h-full';

    const { register, handleSubmit } = useForm<FormValues>();

    const [submitting, setSubmitting] = useState<boolean>(false);

    const [showSuccess, setShowSuccess] = useState<boolean>(false);

    if (showSuccess) {
        return <div className="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div className="fixed inset-0 z-10 overflow-y-auto">
                <div className="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        className="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div className="sm:flex sm:items-start">
                                <div className="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <CheckCircleIcon className="h-9 w-9 text-green-700" />
                                </div>
                                <div className="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 className="text-base font-semibold leading-6 text-gray-900">
                                        Check Your Email
                                    </h3>
                                    <div className="mt-2">
                                        <p className="text-sm text-gray-500">
                                            {/* eslint-disable-next-line max-len */}
                                            If that email address is associated with an account, we’ll you a link which will allow you to reset your password.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <a
                                href="/"
                                type="button"
                                className="inline-flex w-full justify-center rounded-md border border-transparent bg-green-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <ArrowLeftIcon className="w-4 h-4 mt-1 sm:mt-0.5 mr-0.5" />
                                Return to Log In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>;
    }

    const onSubmit: SubmitHandler<FormValues> = (data) => {
        setSubmitting(true);

        const success = () => {
            setShowSuccess(true);
        };

        Submit(data, GetCsrfDataSetFromElement(reactContainer))
            .then(success)
            .catch(success);
    };

    return (
        <>
            <div className="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
                <div className="sm:mx-auto sm:w-full sm:max-w-md">
                    <h2 className="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                        Reset Your Password
                    </h2>
                </div>
                <div className="mt-2 sm:mx-auto sm:w-full sm:max-w-md">
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
                                        className="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none sm:text-sm border-gray-300 focus:border-cyan-500 focus:ring-cyan-500"
                                        disabled={submitting}
                                    />
                                </div>
                            </div>
                            <div>
                                <button
                                    type="submit"
                                    className="flex w-full justify-center rounded-md border border-transparent bg-cyan-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                                    disabled={submitting}
                                >
                                    Send Reset Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </>
    );
};

export default PasswordResetPage;
