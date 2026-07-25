<x-app-layout>
    <x-slot name="header"><x-smart.page-header eyebrow="Smart CRM" title="Novo lead" subtitle="Cadastre uma nova oportunidade comercial." /></x-slot>
    <div class="py-8"><div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8"><div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><form method="POST" action="{{ route('leads.store') }}">@include('crm.leads._form')</form></div></div></div>
</x-app-layout>
