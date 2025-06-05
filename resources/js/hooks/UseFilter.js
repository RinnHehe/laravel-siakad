import { router } from '@inertiajs/react';
import { debounce, isEqual, pickBy } from 'lodash';
import { useCallback, useEffect, useRef } from 'react';

export default function UseFilter({ route, values = {}, only = [], wait = 300 }) {
    const previousValues = useRef(values);

    const reload = useCallback(
        debounce((query) => {
            const cleanedQuery = pickBy(query, (value) => value !== null && value !== '');
            router.get(route, cleanedQuery, {
                only,
                preserveState: true,
                preserveScroll: true,
            });
        }, wait),
        [route, only, wait],
    );

    useEffect(() => {
        if (values && Object.keys(values).length > 0 && !isEqual(values, previousValues.current)) {
            reload(values);
            previousValues.current = values;
        }
    }, [values, reload]);

    return { values };
}
