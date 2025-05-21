import Banner from "@/Components/Banner";
import { Card, CardContent } from "@/Components/ui/card";
import { Toaster } from "@/Components/ui/toaster";
import { Head, usePage } from "@inertiajs/react";
import { toast } from 'sonner';
import { flashMessage } from '@/lib/utils';
import HeaderStudentLayout from "./Partials/HeaderStudentLayout";
import { useEffect } from "react";

export default function StudentLayout({children, title}){
    const checkFee = usePage().props.checkFee;
    const { url } = usePage();
    const flash = flashMessage(usePage());
    useEffect(() => {
        if (flash && flash.message && flash.type == 'warning') toast[flash.type](flash.message);
    }, [flash]);

    return (
        <>
        <Head title={title} />
        <Toaster position='top-center' richColors/>
        <div className="min-h-full">
            <div className="pb-32 bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700">
                {/* Header Layout */}
                <HeaderStudentLayout url={url}/>
            </div>
            <main className="px-6 pb-12 -mt-32 lg:px-28">
                <Card>
                    <CardContent className="p-6">
                        {children}
                    </CardContent>
                </Card>
                {/* CheckFee*/}
                {checkFee == false && <Banner message=" Harap melakukan pembayaran uang kuliah tunggal terlebih dahulu"/>}
            </main>
        </div>
        </>
    )
}