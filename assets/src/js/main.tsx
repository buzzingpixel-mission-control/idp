import React from 'react';
import { createRoot } from 'react-dom/client';
import LogInPage from './LogIn/LogInPage';
import PasswordResetPage from './PasswordReset/PasswordResetPage';
import PasswordResetWithTokenPage from './PasswordResetWithToken/PasswordResetWithTokenPage';

const reactContainer = document.querySelector('[data-react-container]') as HTMLDivElement;

if (reactContainer) {
    const root = createRoot(reactContainer);

    switch (reactContainer.dataset.reactContainer) {
        case 'log-in':
            root.render(<LogInPage reactContainer={reactContainer} />);
            break;
        case 'password-reset':
            root.render(<PasswordResetPage reactContainer={reactContainer} />);
            break;
        case 'password-reset-with-token':
            root.render(<PasswordResetWithTokenPage reactContainer={reactContainer} />);
            break;
        default:
    }
}
