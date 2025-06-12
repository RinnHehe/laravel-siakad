import { Button } from '@/Components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/Components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover';
import { cn } from '@/lib/utils';
import { IconCaretDown, IconCheck, IconSearch } from '@tabler/icons-react';
import { useState } from 'react';

export default function ComboBox({ items, selectedItem, onSelect, placeholder = 'Pilih item...' }) {
    const [open, setOpen] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');

    const handleSelect = (value) => {
        onSelect(value);
        setOpen(false);
        setSearchQuery('');
    };

    const filteredItems = items.filter((item) =>
        item.label.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <PopoverTrigger asChild>
                <Button
                    variant="outline"
                    role="combobox"
                    aria-expanded={open}
                    className="w-full justify-between font-normal"
                    size="xl"
                >
                    {items.find((item) => item.value === selectedItem)?.label ?? placeholder}
                    <IconCaretDown className="ml-2 size-4 shrink-0 opacity-50" />
                </Button>
            </PopoverTrigger>
            <PopoverContent className="p-0" align="start">
                <Command shouldFilter={false}>
                    <div className="flex items-center border-b px-3">
                        <IconSearch className="mr-2 size-4 shrink-0 opacity-50" />
                        <CommandInput
                            placeholder="Cari mahasiswa..."
                            value={searchQuery}
                            onValueChange={setSearchQuery}
                            className="h-11 border-0 focus:ring-0 focus-visible:ring-0"
                        />
                    </div>
                    <CommandList>
                        <CommandEmpty className="py-6 text-center text-sm">
                            Mahasiswa tidak ditemukan.
                        </CommandEmpty>
                        <CommandGroup className="max-h-[300px] overflow-auto">
                            {filteredItems.map((item) => (
                                <CommandItem
                                    key={item.value}
                                    value={item.value}
                                    onSelect={() => handleSelect(item.value)}
                                    className="flex cursor-pointer items-center justify-between px-4 py-3 hover:bg-accent"
                                >
                                    <span className="truncate">{item.label}</span>
                                    {selectedItem === item.value && (
                                        <IconCheck className="ml-2 size-4 flex-shrink-0" />
                                    )}
                                </CommandItem>
                            ))}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}
