import {
  IconBooks,
  IconBuildingSkyscraper,
  IconCalendar,
  IconCalendarTime,
  IconCircleKey,
  IconDoor,
  IconDroplets,
  IconLayout2,
  IconLogout2,
  IconMoneybag,
  IconSchool,
  IconUser,
  IconUsers,
  IconUsersGroup,
} from '@tabler/icons-react';
import NavLink from '@/Components/NavLink';

export default function SidebarResponsive({ url }) {
    return (
        <nav className="mt-4 flex flex-1 flex-col">
            <ul role="list" className="flex flex-1 flex-col">
                <NavLink url="#" active={url.startsWith('/admin/dashboard')} title="Dashboard" icon={IconLayout2} />
                <div className="px-3 py-2 text-xs font-medium text-white">Master</div>
                <NavLink
                    url="#"
                    active={url.startsWith('/admin/faculties')}
                    title="Fakultas"
                    icon={IconBuildingSkyscraper}
                />
                <NavLink
                    url="#"
                    active={url.startsWith('/admin/departments')}
                    title="Program Studi"
                    icon={IconSchool}
                />
                <NavLink
                    url="#"
                    active={url.startsWith('/admin/academics-years')}
                    title="Tahun Ajaran"
                    icon={IconCalendarTime}
                />
                <NavLink url="#" active={url.startsWith('/admin/classrooms')} title="Kelas" icon={IconDoor} />
                <NavLink url="#" active={url.startsWith('/admin/roles')} title="Peran" icon={IconCircleKey} />

                <div className="px-3 py-2 text-xs font-medium text-white">Pengguna</div>
                <NavLink url="#" active={url.startsWith('/admin/students')} title="Mahasiswa" icon={IconUsers} />
                <NavLink url="#" active={url.startsWith('/admin/teachers')} title="Dosen" icon={IconUsersGroup} />
                <NavLink url="#" active={url.startsWith('/admin/operators')} title="Operator" icon={IconUser} />

                <div className="px-3 py-2 text-xs font-medium text-white">Akademik</div>
                <NavLink url="#" active={url.startsWith('/admin/courses')} title="Mata Kuliah" icon={IconBooks} />
                <NavLink url="#" active={url.startsWith('/admin/schedules')} title="Jadwal" icon={IconCalendar} />

                <div className="px-3 py-2 text-xs font-medium text-white">Pembayaran</div>
                <NavLink
                    url="#"
                    active={url.startsWith('/admin/fees')}
                    title="Uang Kuliah Tunggal"
                    icon={IconMoneybag}
                />
                <NavLink
                    url="#"
                    active={url.startsWith('/admin/fee-groups')}
                    title="Golongan UKT"
                    icon={IconDroplets}
                />

                <div className="px-3 py-2 text-xs font-medium text-white">Lainnya</div>
                <NavLink url="#" active={url.startsWith('/logout')} title="Logout" icon={IconLogout2} />
            </ul>
        </nav>
    );
}
