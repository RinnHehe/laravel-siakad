import ApplicationLogo from '@/Components/ApplicationLogo';
import { Disclosure } from '@headlessui/react';
import { IconX, IconLayoutSidebar, IconLogout2, IconChevronDown } from '@tabler/icons-react';
import NavigationMenu from '@/Components/NavigationMenu';
import { Link } from '@inertiajs/react';
import { Avatar, AvatarFallback } from '@/Components/ui/avatar';
import { 
    DropdownMenu, 
    DropdownMenuTrigger, 
    DropdownMenuContent, 
    DropdownMenuItem, 
    DropdownMenuSeparator 
} from '@/Components/ui/dropdown-menu';
import { Button } from '@/Components/ui/button';
import { cn } from '@/lib/utils';

export default function HeaderStudentLayout({url}) {
    return (
        <>
            <Disclosure 
                as="nav" 
                className="py-4 border-b border-blue300 border-opacity-25 bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 lg:border-none"
            >
                {({ open }) => (
                    <>
                        <div className="px-6 lg:px-24">
                            <div className="relative flex items-center justify-between h-16">
                                <div className="flex items-center">
                                    <ApplicationLogo 
                                        bgLogo='from-orange-500 via-orange-600 to-orange-600'
                                        colorLogo='text-white'
                                        colorText='text-white'
                                    />
                                </div>
                                
                                {/* mobile */}
                                <div className="flex lg:hidden">
                                    <Disclosure.Button className='relative inline-flex items-center justify-center p-2 text-white rounded-xl hover:text-white focus:outline-none'>
                                        <span className="absolute -inset-0.5"></span>
                                        {open ? (
                                            <IconX className="block size-6" />
                                        ) : (
                                            <IconLayoutSidebar className="block size-6" />
                                        )}
                                    </Disclosure.Button>
                                </div>
                                
                                <div className="hidden lg:ml-4 lg:block">
                                    <div className="flex items-center">
                                        <div className="hidden lg:mx-10 lg:block">
                                            <div className="flex space-x-4">
                                                <NavigationMenu 
                                                    url="#"
                                                    active={url.startsWith('students/dashboard')}
                                                    title='Dashboard'
                                                />
                                                <NavigationMenu 
                                                    url="#"
                                                    active={url.startsWith('students/schedules')}
                                                    title='Jadwal'
                                                />
                                                <NavigationMenu 
                                                    url="#"
                                                    active={url.startsWith('students/study-plans')}
                                                    title='Kartu Rencana Studi'
                                                />
                                                <NavigationMenu 
                                                    url="#"
                                                    active={url.startsWith('students/study-results')}
                                                    title='Kartu Hasil Studi'
                                                />
                                                <NavigationMenu 
                                                    url="#"
                                                    active={url.startsWith('students/fees')}
                                                    title='Pembayaran'
                                                />
                                            </div>
                                        </div>
                                        
                                        {/* profile dropdown */}
                                        <DropdownMenu>
                                            <DropdownMenuTrigger asChild>
                                                <Button 
                                                    variant="blue"
                                                    size="xl"
                                                    className="data-[state=open]:bg-orange-500 data-[state=open]:text-white"
                                                >
                                                    <Avatar className="size-8 rounded-lg">
                                                        <AvatarFallback className="text-blue-600 rounded-lg">X</AvatarFallback>
                                                    </Avatar>
                                                    
                                                    <div className="grid flex-1 text-sm leading-tight text-left">
                                                        <span className="font-semibold truncate">Luffy</span>
                                                        <span className="text-xs truncate">luffy@siakubwa.test</span>
                                                    </div>
                                                    
                                                    <IconChevronDown className="ml-auto" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            
                                            <DropdownMenuContent 
                                                className="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg" 
                                                side="bottom"
                                                align="end"
                                                sideOffset={4}
                                            >
                                                <div className="p-0 font-normal">
                                                    <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                                        <Avatar className="rounded-lg size-8">
                                                            <AvatarFallback className="text-blue-600 rounded-lg">X</AvatarFallback>
                                                        </Avatar>
                                                        
                                                        <div className="grid flex-1 text-sm leading-tight text-left">
                                                            <span className="font-semibold truncate">Luffy</span>
                                                            <span className="text-xs truncate">luffy@siakubwa.test</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <DropdownMenuSeparator />
                                                
                                                <DropdownMenuItem asChild>
                                                    <Link href="#" className="flex items-center gap-2">
                                                        <IconLogout2 className="size-4" />
                                                        Logout
                                                    </Link>
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <Disclosure.Panel className="lg:hidden">
                            <div className="pb-3">
                                <div className="space-y-1">
                                    <div className="px-3 py-2 mx-2 text-white bg-blue-700 rounded-lg">
                                        <Link href="#" className="block">
                                            Dashboard
                                        </Link>
                                    </div>
                                    <div className="px-3 py-2 mx-2 text-white">
                                        <Link href="#" className="block">
                                            Jadwal
                                        </Link>
                                    </div>
                                    <div className="px-3 py-2 mx-2 text-white">
                                        <Link href="#" className="block">
                                            Kartu Rencana Studi
                                        </Link>
                                    </div>
                                    <div className="px-3 py-2 mx-2 text-white">
                                        <Link href="#" className="block">
                                            Kartu Hasil Studi
                                        </Link>
                                    </div>
                                    <div className="px-3 py-2 mx-2 text-white">
                                        <Link href="#" className="block">
                                            Pembayaran
                                        </Link>
                                    </div>
                                    
                                    <div className="mt-4 py-2 mx-2">
                                        <div className="flex items-center px-3 py-2 bg-blue-600 rounded-lg">
                                            <Avatar className="size-8 rounded-lg">
                                                <AvatarFallback className="text-blue-600 rounded-lg">X</AvatarFallback>
                                            </Avatar>
                                            <div className="ml-2 flex-1">
                                                <div className="text-sm font-semibold text-white">Monkey D Luffy</div>
                                                <div className="text-xs text-white">luffy@siakubwa.test</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Disclosure.Panel>
                    </>
                )}
            </Disclosure>
        </>
    )
}
