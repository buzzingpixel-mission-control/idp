import React, { useState } from 'react';
import { SubmitHandler, useForm } from 'react-hook-form';
import { ArrowLeftIcon, CheckCircleIcon, XCircleIcon } from '@heroicons/react/20/solid';
import { FormValues } from './FormValues';
import Submit from './Submit';
import GetCsrfDataSetFromElement from '../GetCsrfDataSetFromElement';

type DataSet = {
    emailAddress: string;
};

const PasswordResetWithTokenPage = (
    { reactContainer }: { reactContainer: HTMLDivElement },
) => {
    reactContainer.className += ' h-full';

    const { emailAddress } = reactContainer.dataset as DataSet;

    const {
        register,
        handleSubmit,
    } = useForm<FormValues>();

    const [
        submitting,
        setSubmitting,
    ] = useState<boolean>(false);

    const [
        showSuccess,
        setShowSuccess,
    ] = useState<boolean>(false);

    const [
        errorMessage,
        setErrorMessage,
    ] = useState<string>('');

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
                                        Success
                                    </h3>
                                    <div className="mt-2">
                                        <p className="text-sm text-gray-500">
                                            {/* eslint-disable-next-line max-len */}
                                            Your password has been set. You can now log in with that password.
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

        if (errorMessage) {
            setErrorMessage('');
        }

        Submit(data, GetCsrfDataSetFromElement(reactContainer))
            .then(() => {
                setShowSuccess(true);
            })
            .catch((error) => {
                setErrorMessage(error.message);

                setSubmitting(false);
            });
    };

    return (
        <>
            <div className="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
                <div className="sm:mx-auto sm:w-full sm:max-w-md">
                    <h2 className="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                        Reset Your Password
                    </h2>
                    <h3 className="mt-1 text-center text-base font-normal tracking-tight text-gray-600">
                        {emailAddress}
                    </h3>
                </div>
                <div className="mt-2 sm:mx-auto sm:w-full sm:max-w-md">

                    <div className={errorMessage ? 'block' : 'hidden'}>
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
                                    New Password
                                </label>
                                <div className="mt-1">
                                    <input
                                        {...register('password')}
                                        id="password"
                                        type="password"
                                        // required
                                        className="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:outline-none sm:text-sm border-gray-300 focus:border-cyan-500 focus:ring-cyan-500"
                                        disabled={submitting}
                                    />
                                </div>
                            </div>
                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                    Confirm New Password
                                </label>
                                <div className="mt-1">
                                    <input
                                        {...register('passwordConfirm')}
                                        id="passwordConfirm"
                                        type="password"
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
                                    Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </>
    );
};

export default PasswordResetWithTokenPage;
