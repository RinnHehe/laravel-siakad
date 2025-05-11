import { useState, useEffect } from 'react';
import { Fragment } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { Head, Link, usePage } from '@inertiajs/react';
import { Toaster } from 'sonner';
import { toast } from 'sonner';
import { IconLayoutSidebar } from '@tabler/icons-react';
import { Avatar, AvatarFallback } from '@/Components/ui/avatar';
import { cn } from '@/lib/utils';
import { flashMessage } from '@/lib/utils';
import Sidebar from '@/Layouts/Partials/Sidebar';
import SidebarResponsive from '@/Layouts/Partials/SidebarResponsive';

export default function AppLayout({title, children}) {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  
  const { url } = usePage();
  
  const flash = flashMessage(usePage());
  
  useEffect(() => {
    if(flash && flash.message && flash.type === 'warning') toast[flash.type](flash.message);
  }, [flash]);
  
  return (
    <>
      <Head title={title}>
        <style>{`
          /* Hide scrollbar for Chrome, Safari and Opera */
          .scrollbar-hide::-webkit-scrollbar {
            display: none;
          }
          
          /* Hide scrollbar for IE, Edge and Firefox */
          .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
          }
        `}</style>
      </Head>
      
      <Toaster position="top-center" richColors />
      
      <div>
        <Transition.Root show={sidebarOpen} as={Fragment}>
          <Dialog as="div" className="relative z-50 lg:hidden" onClose={setSidebarOpen}>
            <Transition.Child
              as={Fragment}
              enter="transition-opacity ease-linear duration-300"
              enterFrom="opacity-0"
              enterTo="opacity-100"
              leave="transition-opacity ease-linear duration-300"
              leaveFrom="opacity-100"
              leaveTo="opacity-0"
            >
              <div className="fixed inset-0 bg-gray-900/80"></div>
            </Transition.Child>
            
            <div className="fixed inset-0 flex">
              <Transition.Child
                as={Fragment}
                enter="transition ease-in-out duration-300 transform"
                enterFrom="-translate-x-full"
                enterTo="translate-x-0"
                leave="transition ease-in-out duration-300 transform"
                leaveFrom="translate-x-0"
                leaveTo="-translate-x-full"
              >
                <Dialog.Panel className="relative flex flex-1 w-full max-w-xs">
                  <Transition.Child
                    as={Fragment}
                    enter="ease-in-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in-out duration-300"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                  >
                    <div className="absolute top-0 flex justify-center w-16 pt-5 left-full">
                      <button
                        type="button"
                        className="m-2.5 p-2.5"
                        onClick={() => setSidebarOpen(false)}
                      >
                        <span className="sr-only">Close sidebar</span>
                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                  </Transition.Child>
                  
                  <div className="flex grow flex-col gap-y-5 overflow-y-auto overflow-x-hidden bg-gradient-to-b from-blue-500 via-blue-600 to-blue-700 px-6 pb-2 scrollbar-hide">
                    {/* sidebar responsive */}
                    <SidebarResponsive url={url} />
                  </div>
                </Dialog.Panel>
              </Transition.Child>
            </div>
          </Dialog>
        </Transition.Root>
        
        <div className="hidden p-2.5 lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
          <div className="flex flex-col px-4 overflow-y-auto overflow-x-hidden border grow gap-y-5 rounded-xl bg-gradient-to-b from-blue-500 via-blue-600 to-blue-700 scrollbar-hide">
            <nav className="flex flex-col flex-1">
              <ul className="flex flex-col flex-1 gap-y-7 scrollbar-hide">
                <Sidebar url={url} />
              </ul>
            </nav>
          </div>
        </div>
        
        <div className="sticky top-0 z-40 flex items-center p-4 bg-white shadow-sm gap-x-6 sm:px-6 lg:hidden">
          <button
            type="button"
            className="m-2.5 p-2.5 text-gray-700 lg:hidden"
            onClick={() => setSidebarOpen(true)}
          >
            <IconLayoutSidebar className="w-6 h-6" />
          </button>
          
          <div className="flex-1 text-sm font-semibold leading-6 text-foreground">
            {title}
          </div>
          
          <Link href="#">
            <Avatar>
              <AvatarFallback>X</AvatarFallback>
            </Avatar>
          </Link>
        </div>
        
        <main className="py-4 lg:pl-72">
          <div className="px-4">
            {children}
          </div>
        </main>
      </div>
    </>
  );
}