

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const formatSmartMoney = (input) => {
    const digits = input.value.replace(/\D/g, '');

    if (digits === '') {
        input.value = '';
        return;
    }

    const amount = Number.parseInt(digits, 10) / 100;
    input.value = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(amount);
};

const initializeSmartMoney = () => {
    document.querySelectorAll('[data-smart-money]').forEach((input) => {
        if (input.dataset.smartMoneyReady === '1') {
            return;
        }

        input.dataset.smartMoneyReady = '1';
        input.addEventListener('input', () => formatSmartMoney(input));
    });
};

document.addEventListener('DOMContentLoaded', initializeSmartMoney);
document.addEventListener('alpine:initialized', initializeSmartMoney);
