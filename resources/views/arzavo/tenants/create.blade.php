<form action="{{ route('arzavo.tenants.store') }}" method="POST" class="space-y-4">
    @csrf
    <input type="text" name="name" placeholder="Tenant Name" class="w-full border-primary border-rounded p-3 mb-4">
    <input type="text" name="subdomain" placeholder="Subdomain" class="w-full border-primary border-rounded p-3 mb-4">
    <input type="text" name="custom_domain" placeholder="Custom Domain" class="w-full border-primary border-rounded p-3 mb-4">

    <button type="submit" class="w-full bg-invert text-invert p-3 border-rounded">
        Create Tenant
    </button>
</form>