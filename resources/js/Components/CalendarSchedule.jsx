import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { useRef } from 'react';

export default function CalendarSchedule({ days = [], scheduleTable = {}, student = null, debug = null }) {
    const container = useRef(null);
    const containerNav = useRef(null);
    const containerOffset = useRef(null);

    // Debug: tampilkan data yang diterima
    console.log('=== CALENDAR SCHEDULE DEBUG ===');
    console.log('Days received:', days);
    console.log('Schedule table received:', scheduleTable);
    console.log('Schedule table keys:', Object.keys(scheduleTable));
    console.log('Schedule table entries:', Object.entries(scheduleTable));
    console.log('Debug data received:', debug);
    console.log('All props received:', { days, scheduleTable, student, debug });
    console.log('================================');

    // Helper function to convert time to minutes from 7:00
    const timeToMinutes = (time) => {
        const [hour, minute] = time.split(':').map(Number);
        const startOfDay = 7 * 60; // 7:00 AM in minutes
        const timeInMinutes = hour * 60 + minute;
        return timeInMinutes - startOfDay;
    };

    // Calculate grid row position (each row represents 30 minutes)
    const calculateGridRow = (time) => {
        const minutesFromStart = timeToMinutes(time);
        const rowPosition = Math.floor(minutesFromStart / 30) + 2; // +2 for header offset

        console.log(`Time ${time} -> ${minutesFromStart} minutes -> row ${rowPosition}`);
        return rowPosition;
    };

    const calculateColumnStart = (day) => {
        const dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        const colIndex = dayNames.indexOf(day) + 2; // +2 because first column is for time labels
        console.log(`Day ${day} -> column ${colIndex}`);
        return colIndex;
    };

    // Fixed color assignment - use consistent colors based on course name
    const getColorForCourse = (courseName) => {
        const colors = [
            'bg-gradient-to-br from-blue-500 to-blue-600 text-white',
            'bg-gradient-to-br from-green-500 to-green-600 text-white',
            'bg-gradient-to-br from-red-500 to-red-600 text-white',
            'bg-gradient-to-br from-purple-500 to-purple-600 text-white',
            'bg-gradient-to-br from-orange-500 to-orange-600 text-white',
            'bg-gradient-to-br from-teal-500 to-teal-600 text-white',
            'bg-gradient-to-br from-indigo-500 to-indigo-600 text-white',
            'bg-gradient-to-br from-pink-500 to-pink-600 text-white',
        ];

        // Use course name hash to get consistent color
        let hash = 0;
        for (let i = 0; i < courseName.length; i++) {
            hash = courseName.charCodeAt(i) + ((hash << 5) - hash);
        }
        const index = Math.abs(hash) % colors.length;
        return colors[index];
    };

    // Generate time labels for the grid
    const generateTimeLabels = () => {
        const times = [];
        for (let hour = 7; hour <= 17; hour++) {
            times.push(
                <div key={`hour-${hour}`}>
                    <div className="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-500">
                        {hour.toString().padStart(2, '0')}.00
                    </div>
                </div>
            );
            // Add half-hour divider except for last hour
            if (hour < 17) {
                times.push(<div key={`half-${hour}`}></div>);
            }
        }
        return times;
    };

    return (
        <div ref={container} className="isolate hidden flex-auto flex-col overflow-auto bg-white lg:flex">
            <div
                style={{ width: '165%' }}
                className="flex max-w-full flex-none flex-col sm:max-w-none md:max-w-full lg:max-w-full xl:max-w-full"
            >
                {/* Header with day names */}
                <div
                    ref={containerNav}
                    className="sticky top-0 z-30 flex-none bg-white shadow ring-1 ring-black ring-opacity-5 sm:pr-8"
                >
                    <div className="grid grid-cols-7 text-sm leading-6 text-gray-900 sm:hidden">
                        {days.map((day, index) => (
                            <button key={`mobile-day-${index}`} type="button" className="flex flex-col items-center pb-3 pt-2">
                                {day}
                            </button>
                        ))}
                    </div>
                    <div className="-mr-px hidden grid-cols-7 divide-x divide-gray-100 border-r border-gray-100 text-sm leading-6 text-gray-900 sm:grid">
                        <div className="col-end-1 w-14" />
                        {days.map((day, index) => (
                            <div key={`desktop-day-${index}`} className="flex items-center justify-center py-3 font-semibold">
                                <span>{day}</span>
                            </div>
                        ))}
                    </div>
                </div>

                <div className="flex flex-auto">
                    <div className="sticky left-0 z-10 w-14 flex-none bg-white ring-1 ring-gray-100" />
                    <div className="grid flex-auto grid-cols-1 grid-rows-1">
                        {/* Time grid - horizontal lines */}
                        <div
                            className="col-start-1 col-end-2 row-start-1 grid divide-y divide-gray-100"
                            style={{ gridTemplateRows: 'repeat(20, minmax(3.5rem, 1fr))' }}
                        >
                            <div ref={containerOffset} className="row-end-1 h-7"></div>
                            {generateTimeLabels()}
                        </div>

                        {/* Vertical grid lines */}
                        <div className="hidden grid-cols-7 col-start-1 col-end-2 grid-rows-1 divide-x divide-gray-100 sm:grid sm:grid-cols-7">
                            <div className="col-start-1 row-span-full" />
                            <div className="col-start-2 row-span-full" />
                            <div className="col-start-3 row-span-full" />
                            <div className="col-start-4 row-span-full" />
                            <div className="col-start-5 row-span-full" />
                            <div className="col-start-6 row-span-full" />
                            <div className="col-start-7 row-span-full" />
                            <div className="w-8 col-start-8 row-span-full" />
                        </div>

                        {/* Schedule events */}
                        <ol
                            className="grid grid-cols-1 col-start-1 col-end-2 row-start-1 sm:grid-cols-7 sm:pr-8"
                            style={{ gridTemplateRows: '1.75rem repeat(22, minmax(0, 1fr))' }}
                        >
                            {/* Debug output */}
                            {(() => {
                                console.log('About to render schedules...');
                                console.log('scheduleTable type:', typeof scheduleTable);
                                console.log('scheduleTable isEmpty:', Object.keys(scheduleTable).length === 0);
                                return null;
                            })()}

                            {!scheduleTable || Object.keys(scheduleTable).length === 0 ? (
                                <li className="col-span-7 flex items-center justify-center py-8">
                                    <p className="text-gray-500">Tidak ada jadwal ditemukan</p>
                                </li>
                            ) : (
                                Object.entries(scheduleTable).flatMap(([startTime, daySchedules]) => {
                                    console.log(`Processing start time: ${startTime}`, daySchedules);

                                    return Object.entries(daySchedules).map(([day, schedule]) => {
                                        const startRow = calculateGridRow(startTime);
                                        const endRow = calculateGridRow(schedule.end_time);
                                        const column = calculateColumnStart(day);
                                        const colorClass = getColorForCourse(schedule.course);

                                        console.log(`Rendering schedule:`, {
                                            course: schedule.course,
                                            day: day,
                                            startTime: startTime,
                                            endTime: schedule.end_time,
                                            startRow: startRow,
                                            endRow: endRow,
                                            column: column
                                        });

                                        // Validate grid position
                                        if (startRow < 2 || endRow > 23 || column < 2 || column > 8) {
                                            console.warn(`Invalid grid position for ${schedule.course}:`, {
                                                startRow, endRow, column
                                            });
                                            return null;
                                        }

                                        // Ensure we have a valid span
                                        const rowSpan = Math.max(1, endRow - startRow);

                                        return (
                                            <li
                                                key={`${startTime}-${day}-${schedule.course}`}
                                                className="relative flex mt-px"
                                                style={{
                                                    gridRow: `${startRow} / span ${rowSpan}`,
                                                    gridColumn: column,
                                                }}
                                            >
                                                <div
                                                    className={cn(
                                                        'absolute flex flex-col p-3 overflow-y-auto text-xs leading-5 rounded-lg group inset-1 shadow-lg border border-white/20',
                                                        colorClass
                                                    )}
                                                >
                                                    <p className="order-1 font-bold text-sm mb-1 leading-tight">
                                                        {schedule.course}
                                                    </p>
                                                    <p className="text-white/90 font-medium">
                                                        {startTime} - {schedule.end_time}
                                                    </p>
                                                </div>
                                            </li>
                                        );
                                    });
                                })
                            )}
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    );
}