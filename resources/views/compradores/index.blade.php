@extends('layouts.app')

@section('title', 'Directorio de Compradores de Mineral')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false, modalMode: 'create', selectedComprador: {} }">
    
    <!-- Header Page Banner -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-500">
                    <i class="fa-solid fa-handshake text-2xl"></i>
                </div>
                Compradores de Mineral
            </h1>
            <p class="text-sm text-slate-400 mt-1">Directorio comercial de empresas fundidoras, comercializadoras y compradores autorizados.</p>
        </div>

        <button @click="modalMode = 'create'; selectedComprador = { estado: 'activo' }; modalOpen = true" 
                class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus text-base"></i> Registrar Comprador
        </button>
    </div>

    <!-- Search & Filter Bar -->
    <div class="glass-card p-4 rounded-2xl">
        <form method="GET" action="{{ route('compradores.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por Razón Social, NIT o Contacto..." class="w-full pl-10 pr-4 py-2.5 text-sm">
            </div>

            <div>
                <select name="estado" class="w-full py-2.5 text-sm">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Solo Activos</option>
                    <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Solo Inactivos</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-vibrant-indigo flex-1 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-filter mr-1.5"></i> Filtrar
                </button>
                @if(request()->hasAny(['buscar', 'estado']))
                    <a href="{{ route('compradores.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-sm font-semibold transition">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Compradores Table Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 border-b border-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Razón Social / Empresa</th>
                        <th class="px-6 py-4">NIT / CI</th>
                        <th class="px-6 py-4">Contacto & Teléfono</th>
                        <th class="px-6 py-4">Email & Dirección</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm text-slate-200">
                    @forelse($compradores as $comprador)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $comprador->razon_social }}
                                @if($comprador->notas)
                                    <p class="text-xs text-slate-400 font-normal italic mt-0.5">{{ Str::limit($comprador->notas, 45) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-amber-400">
                                {{ $comprador->nit_ci ?? 'S/N' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-200">{{ $comprador->contacto_nombre ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ $comprador->telefono ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-300">{{ $comprador->email ?? '-' }}</div>
                                <div class="text-xs text-slate-500 truncate max-w-xs">{{ $comprador->direccion ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($comprador->estado === 'activo')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="modalMode = 'edit'; selectedComprador = {{ json_encode($comprador) }}; modalOpen = true"
                                        class="p-2 rounded-lg bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 border border-amber-500/30 transition"
                                        title="Editar Comprador">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                
                                <form action="{{ route('compradores.destroy', $comprador->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este comprador?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 border border-rose-500/30 transition" title="Eliminar">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-handshake-slash text-4xl mb-3 block text-slate-600"></i>
                                No se encontraron compradores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create / Edit Modal -->
    <div x-show="modalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl border border-amber-500/30" @click.away="modalOpen = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-building text-amber-500"></i>
                    <span x-text="modalMode === 'create' ? 'Registrar Nuevo Comprador' : 'Editar Comprador'"></span>
                </h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="modalMode === 'create' ? '{{ route('compradores.store') }}' : '/compradores/' + selectedComprador.id" method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="modalMode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Razón Social / Nombre Empresa *</label>
                    <input type="text" name="razon_social" x-model="selectedComprador.razon_social" required placeholder="Ej: Comercializadora Vinto S.A." class="w-full py-2.5 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">NIT / CI</label>
                        <input type="text" name="nit_ci" x-model="selectedComprador.nit_ci" placeholder="Ej: 1029384029" class="w-full py-2.5 text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Persona de Contacto</label>
                        <input type="text" name="contacto_nombre" x-model="selectedComprador.contacto_nombre" placeholder="Ej: Lic. Carlos Pérez" class="w-full py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Teléfono / Celular</label>
                        <input type="text" name="telefono" x-model="selectedComprador.telefono" placeholder="Ej: 71234567" class="w-full py-2.5 text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Correo Electrónico</label>
                        <input type="email" name="email" x-model="selectedComprador.email" placeholder="contacto@vinto.com" class="w-full py-2.5 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Dirección / Planta</label>
                    <input type="text" name="direccion" x-model="selectedComprador.direccion" placeholder="Av. Minera N° 450, Potosí" class="w-full py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Estado</label>
                    <select name="estado" x-model="selectedComprador.estado" class="w-full py-2.5 text-sm">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Notas / Observaciones</label>
                    <textarea name="notas" x-model="selectedComprador.notas" rows="2" placeholder="Condiciones comerciales o notas de entrega..." class="w-full py-2.5 text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Guardar Comprador
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
