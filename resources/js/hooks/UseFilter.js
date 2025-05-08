import { router } from '@inertiajs/react';
import pkg from 'lodash';
import { useCallback, useEffect } from 'react';

export default function UseFilter({ route, values, only, wait = 300 }) {
  const { debounce, pickBy } = pkg;

  const reload = useCallback(
    (query) => {
      router.get(route, { data: pickBy(query) }, {
        only: only,
        preserveState: true,
        preserveScroll: true,
      });
    },
    []
  );

  useEffect(() => {
    reload(values);
  }, [values, reload]);
  
  return { values };
}