import HeaderTitle from '@/Components/HeaderTitle';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowLeft, IconCheck, IconDroplets } from '@tabler/icons-react';
import { toast } from 'sonner';

export default function Edit(props) {
    const { data, setData, post, processing, errors, reset } = useForm({
        group: props.feeGroup.group ?? 1,
        amount: props.feeGroup.amount ?? 0,
        _method: props.page_settings.method,
    });

    const onHandleReset = () => {
        reset();
    };

    const onHandleSubmit = (e) => {
        e.preventDefault();

        post(
            route('admin.fee-groups.update', props.feeGroup),
            {
                group: Number(data.group),
                amount: Number(data.amount),
            },
            {
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
            },
        );
    };

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconDroplets}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('admin.fee-groups.index')}>
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
                                <Label htmlFor="group">Golongan UKT</Label>
                                <Input
                                    type="text"
                                    id="group"
                                    name="group"
                                    placeholder="Masukkan golongan UKT"
                                    value={data.group}
                                    onChange={(e) => setData(e.target.name, e.target.value)}
                                    className={errors.group ? 'border-red-500 focus:border-red-500' : ''}
                                />
                                {errors.group && <div className="mt-2 text-sm text-red-600">{errors.group}</div>}
                            </div>
                            <div className="col-span-full">
                                <Label htmlFor="amount">Jumlah</Label>
                                <Input
                                    type="number"
                                    id="amount"
                                    name="amount"
                                    placeholder="Masukkan jumlah"
                                    value={data.amount}
                                    onChange={(e) => setData(e.target.name, e.target.value)}
                                    className={errors.amount ? 'border-red-500 focus:border-red-500' : ''}
                                />
                                {errors.amount && <div className="mt-2 text-sm text-red-600">{errors.amount}</div>}
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
