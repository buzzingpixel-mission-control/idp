import React from 'react';
import { createRoot } from 'react-dom/client';
import LogInPage from './LogIn/LogInPage';

const reactContainer = document.querySelector('[data-react-container]') as HTMLDivElement;

if (reactContainer) {
    const root = createRoot(reactContainer);

    switch (reactContainer.dataset.reactContainer) {
        case 'log-in':
            root.render(<LogInPage reactContainer={reactContainer} />);
            break;
        default:
    }
}
