import React from 'react';
import { Link } from '@inertiajs/react';
import { Avatar, AvatarFallback } from '@/Components/ui/avatar';
import { 
  IconLayout2, 
  IconBuildingSkyscraper, 
  IconSchool, 
  IconCalendarTime, 
  IconDoor, 
  IconCircleKey,
  IconUsers,
  IconUsersGroup,
  IconUser,
  IconBooks,
  IconCalendar,
  IconMoneybag,
  IconDroplets,
  IconLogout2
} from '@tabler/icons-react';

export default function Sidebar({url}) {
  return (
    <nav className="flex flex-col flex-1 overflow-x-hidden scrollbar-hide">
      <ul role="list" className="flex flex-col flex-1 w-full scrollbar-hide">
        <li className="-mx-6">
          <Link
            href="#"
            className="flex items-center px-6 py-3 text-sm font-semibold leading-6 text-white gap-x-4 hover:bg-blue-800 rounded-lg"
          >
            <Avatar>
              <AvatarFallback>X</AvatarFallback>
            </Avatar>
            
            <div className="flex flex-col text-left">
              <span className="font-bold truncate">Monkey D Luffy</span>
              <span className="truncate">Admin</span>
            </div>
          </Link>
        </li>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/dashboard') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconLayout2 className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Dashboard</span>
          </Link>
        </li>
        
        <div className="px-3 py-2 text-xs font-medium text-white">Master</div>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/faculties') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconBuildingSkyscraper className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Fakultas</span>
          </Link>
        </li>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/departments') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconSchool className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Program Studi</span>
          </Link>
        </li>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/academic-years') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconCalendarTime className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Tahun Ajaran</span>
          </Link>
        </li>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/classrooms') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconDoor className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Kelas</span>
          </Link>
        </li>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/roles') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconCircleKey className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Peran</span>
          </Link>
        </li>
        
        <div className="px-3 py-2 text-xs font-medium text-white">Pengguna</div>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/students') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconUsers className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Mahasiswa</span>
          </Link>
        </li>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/teachers') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconUsersGroup className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Dosen</span>
          </Link>
        </li>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/operators') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconUser className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Operator</span>
          </Link>
        </li>
        
        <div className="px-3 py-2 text-xs font-medium text-white">Akademik</div>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/courses') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconBooks className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Mata Kuliah</span>
          </Link>
        </li>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/schedules') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconCalendar className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Jadwal</span>
          </Link>
        </li>
        
        <div className="px-3 py-2 text-xs font-medium text-white">Pembayaran</div>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/fees') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconMoneybag className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Uang Kuliah Tunggal</span>
          </Link>
        </li>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/admin/fee-groups') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconDroplets className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Golongan UKT</span>
          </Link>
        </li>
        
        <div className="px-3 py-2 text-xs font-medium text-white">Lainnya</div>
        
        <li>
          <Link
            href="#"
            className={`flex items-center px-6 py-3 text-sm font-semibold leading-6 gap-x-4 rounded-lg ${
              url.startsWith('/logout') 
                ? 'bg-blue-800 text-white' 
                : 'text-white hover:bg-blue-800'
            }`}
          >
            <IconLogout2 className="w-5 h-5 flex-shrink-0" />
            <span className="truncate">Logout</span>
          </Link>
        </li>
      </ul>
    </nav>
  );
}
