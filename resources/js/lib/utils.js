import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs) {
    return twMerge(clsx(inputs));
}

export function flashMessage(params) {
    return params.props.flash_message;
}

export const deleteAction = (url, { closeModal, ...options } = {}) => {
    const defaultOptions = {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (success) => {
            const flash = flashMessage(success);

            if (flash) {
                toast[flash.type](flash.message);
            }

            if (closeModal && typeof closeModal === 'function') {
                closeModal();
            }
        },
        ...options,
    };

    router.delete(url, defaultOptions);
};

export const formatDateIndo = (dateString) => {
    return format(parseISO(dateString), 'eeee, dd MMMM yyyy', { locale: id });
};

export const formatToRupiah = (amount) => {
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

    return formatter.format(amount);
};

export const STUDYPLANSTATUS = {
    PENDING: 'Pending',
    REJECT: 'Reject',
    APPROVED: 'Approve',
};

export const STUDYPLANSTATUSVARIANT = {
    [STUDYPLANSTATUS.PENDING]: 'secondary',
    [STUDYPLANSTATUS.REJECT]: 'destructive',
    [STUDYPLANSTATUS.APPROVED]: 'success',
};

export const FEESTATUS = {
    PENDING: 'Tertunda',
    SUCCESS: 'Sukses',
    FAILED: 'Gagal',
};

export const FEESTATUSVARIANT = {
    [FEESTATUS.PENDING]: 'secondary',
    [FEESTATUS.SUCCESS]: 'success',
    [FEESTATUS.FAILED]: 'destructive',
};

export const feeCodeGenerator = () => {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    let result = '';

    for (let i = 0; i < 6; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        result += characters[randomIndex];
    }

    return result;
};
