import { Head } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';
import { Toaster } from 'react-hot-toast';
import { Card, CardContent } from '@/Components/ui/card';
import { Banner } from '@/Components/ui/banner';
import HeaderStudentLayout from '@/Layouts/HeaderStudentLayout';
import { useEffect } from 'react';
import toast from 'react-hot-toast';

export default function StudentLayout({children, title}) {
    const { flash } = usePage().props;
    const checkFee = usePage().props.checkFee;
    const url = usePage().url;
    
    console.log(checkFee);
    
    useEffect(() => {
        if(flash && flash.message && flash.type === 'warning') toast[flash.type](flash.message);
    }, [flash]);
    
    return (
        <>
            <Head title={title} />
            
            <Toaster position='top-center' richColors />
            
            <div className="min-h-screen bg-white">
                <HeaderStudentLayout url={url} />
                
                <div className="px-4 py-6 mx-auto max-w-7xl lg:px-8">
                    <Card className="overflow-hidden shadow-lg">
                        <CardContent className="p-4">
                            {children}
                        </CardContent>
                    </Card>
                    
                    {checkFee === false && <Banner message="Harap melakukan pembayaran uang kuliah tunggal terlebih dahulu" />}
                </div>
            </div>
        </>
    )
} 