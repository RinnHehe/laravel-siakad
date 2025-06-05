import HeaderTitle from '@/Components/HeaderTitle';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowLeft, IconBuildingSkyscraper, IconCheck } from '@tabler/icons-react';
import { useRef } from 'react';
import { toast } from 'sonner';

export default function Edit(props) {
    const fileInputLogo = useRef(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        name: props.faculty.name ?? '',
        logo: null,
        _method: props.page_settings.method,
    });

    const onHandleReset = () => {
        reset();
        fileInputLogo.current.value = null;
    };

    const onHandleSubmit = (e) => {
        e.preventDefault();

        // Client-side validation
        const errors = {};
        if (!data.name || data.name.trim() === '') {
            errors.name = 'Nama Jurusan harus diisi';
            toast.error('Nama Jurusan harus diisi');
        }
        if (!data.logo) {
            errors.logo = 'Logo Jurusan harus diisi';
            toast.error('Logo Jurusan harus diisi');
        }

        // If there are validation errors, don't submit
        if (Object.keys(errors).length > 0) {
            return;
        }

        post(props.page_settings.action, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (success) => {
                const flash = flashMessage(success);
                if (flash) {
                    if (flash.type === 'success') {
                        toast.success(flash.message);
                    } else if (flash.type === 'error') {
                        toast.error(flash.message);
                    }
                }
            },
            onError: (errors) => {
                Object.keys(errors).forEach((key) => {
                    toast.error(errors[key]);
                });
            },
        });
    };

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconBuildingSkyscraper}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('admin.faculties.index')}>
                        <IconArrowLeft className="size-4" />
                        Kembali
                    </Link>
                </Button>
            </div>
            <Card>
                <CardContent className="p-6">
                    <form onSubmit={onHandleSubmit}>
                        <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
                            <div className="col-span-full">
                                <Label htmlFor="name">Nama Jurusan</Label>
                                <Input
                                    type="text"
                                    id="name"
                                    name="name"
                                    placeholder="Masukkan Nama Jurusan"
                                    value={data.name}
                                    onChange={(e) => setData(e.target.name, e.target.value)}
                                    className={errors.name ? 'border-red-500 focus:border-red-500' : ''}
                                />
                                {errors.name && <div className="mt-2 text-sm text-red-600">{errors.name}</div>}
                            </div>
                            <div className="col-span-full">
                                <Label htmlFor="logo">Logo Jurusan</Label>
                                <Input
                                    type="file"
                                    id="logo"
                                    name="logo"
                                    placeholder="Masukkan Logo"
                                    onChange={(e) => setData(e.target.name, e.target.files[0])}
                                    ref={fileInputLogo}
                                    className={errors.logo ? 'border-red-500 focus:border-red-500' : ''}
                                />
                                {errors.logo && <div className="mt-2 text-sm text-red-600">{errors.logo}</div>}
                            </div>
                        </div>
                        <div className="mt-8 flex flex-col gap-2 lg:flex-row lg:justify-end">
                            <Button type="button" variant="ghost" size="xl" onClick={onHandleReset}>
                                Reset
                            </Button>
                            <Button type="submit" variant="blue" size="xl" disabled={processing}>
                                <IconCheck className="mr-2 size-4" />
                                Save
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    );
}

Edit.layout = (page) => <AppLayout children={page} title={page.props.page_settings.title} />;
