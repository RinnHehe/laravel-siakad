export function Banner({ message }) {
    return (
        <div className="mb-4 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800 dark:bg-yellow-100 dark:text-yellow-800">
            <span className="font-medium">{message}</span>
        </div>
    );
}
