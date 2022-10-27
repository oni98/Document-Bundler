@php
$bundle_limit = $plan->DatasByProps('bundle_limit');
$page_limit = $plan->DatasByProps('page_limit');
$storage_validity = $plan->DatasByProps('storage_validity');
$default_watermark = $plan->DatasByProps('default_watermark');
$own_watermark = $plan->DatasByProps('own_watermark');

@endphp
<div>
    <table class="table table-bordered">

        <tbody>
            <tr>
                <td width="30%">Bundle Limit </td>
                <td width="30%">

                    <select name="bundle_limit.status" wire:model.defer="bundle_limit.{{ $plan->id }}.status"
                        wire:change="store($event.target.value,'bundle_limit.{{ $plan->id }}.status')"
                        class="form-control">
                        <option value="">STATUS</option>
                        <option value="0" @if (!empty($bundle_limit) && $bundle_limit->status == 0) selected @endif>DISABLE</option>
                        <option value="1" @if (!empty($bundle_limit) && $bundle_limit->status == 1) selected @endif>ENABLE</option>
                    </select>
                    @error('bundle_limit.status')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </td>
                <td width="30%">
                    <input type="checkbox" wire:model.defer="bundle_limit.{{ $plan->id }}.value"
                        @if (!empty($bundle_limit) && $bundle_limit->values == 'unlimited') checked @endif
                        wire:click="store($event.target.value,'bundle_limit.{{ $plan->id }}.value')"
                        name="bundle_limit.value" value="unlimited" id=""> UNLIMITED<br>
                    <input type="number" name="bundle_limit.value" min="1"
                        wire:model.defer="bundle_limit.{{ $plan->id }}.value" placeholder="MIN:1"
                        class="form-control"
                        @if (!empty($bundle_limit)) value="{{ $bundle_limit->values }}" @endif
                        wire:change="store($event.target.value,'bundle_limit.{{ $plan->id }}.value')"
                        wire:keyup="store($event.target.value,'bundle_limit.{{ $plan->id }}.value')">
                    @error('bundle_limit.value')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td width="30%">Page Limit</td>
                <td width="30%">
                    <select name="page_limit.status"
                        wire:change="store($event.target.value,'page_limit.{{ $plan->id }}.status')"
                        wire:model.defer="page_limit.{{ $plan->id }}.status" id="page_limit.status"
                        class="form-control">
                        <option value="">STATUS</option>
                        <option value="0" @if (!empty($page_limit) && $page_limit->status == 0) selected @endif>DISABLE</option>
                        <option value="1" @if (!empty($page_limit) && $page_limit->status == 1) selected @endif>ENABLE</option>
                    </select>
                    @error('page_limit.status')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </td>
                <td width="30%">
                    <input type="checkbox" wire:model.defer="page_limit.{{ $plan->id }}.value"
                        @if (!empty($page_limit) && $page_limit->values == 'unlimited') checked @endif
                        wire:click="store($event.target.value,'page_limit.{{ $plan->id }}.value')"
                        name="page_limit.value" value="unlimited" id=""> UNLIMITED<br>
                    <input type="number"
                        wire:change="store($event.target.value,'page_limit.{{ $plan->id }}.values')"
                        @if (!empty($page_limit)) value="{{ $page_limit->values }}" @endif
                        wire:model.defer="page_limit.{{ $plan->id }}.values"
                        wire:keyup="store($event.target.value,'page_limit.{{ $plan->id }}.values')"
                        name="page_limit.value" placeholder="Per page. ex:60" class="form-control"
                        id="page_limit.value">
                    @error('page_limit.value')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td width="30%">Storage Validity (in days)</td>
                <td width="30%">
                    <select name="storage_validity.status"
                        wire:change="store($event.target.value,'storage_validity.{{ $plan->id }}.status')"
                        wire:model.defer="storage_validity.{{ $plan->id }}.status" id="storage_validity"
                        class="form-control">
                        <option value="">STATUS</option>
                        <option value="0" @if (!empty($storage_validity) && $storage_validity->status == 0) selected @endif>DISABLE</option>
                        <option value="1" @if (!empty($storage_validity) && $storage_validity->status == 1) selected @endif>ENABLE</option>
                    </select>
                    @error('storage_validity.status')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </td>
                <td width="30%">
                    <input type="checkbox"
                        wire:click="store($event.target.value,'storage_validity.{{ $plan->id }}.values')"
                        @if (!empty($storage_validity) && $storage_validity->values == 'unlimited') checked @endif name="storage_validity.value"
                        wire:model.defer="storage_validity.{{ $plan->id }}.values" value="unlimited"
                        id="storage_validity.{{ $plan->id }}.values">
                    UNLIMITED<br>
                    <input type="number"
                        wire:change="store($event.target.value,'storage_validity.{{ $plan->id }}.values')"
                        @if (!empty($storage_validity)) value="{{ $storage_validity->values }}" @endif
                        wire:keyup="store($event.target.value,'storage_validity.{{ $plan->id }}.values')"
                        name="storage_validity.value" wire:model.defer="storage_validity.{{ $plan->id }}.value"
                        placeholder="in days. ex:60" class="form-control" id="storage_validity.value">
                    @error('storage_validity.value')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td width="30%">Default Watermark</td>
                <td colspan="2">
                    <select name="default_watermark.status"
                        wire:change="store($event.target.value,'default_watermark.{{ $plan->id }}.status')"
                        wire:model.defer="default_watermark.{{ $plan->id }}.status" id="default_watermark"
                        class="form-control">
                        <option value="">STATUS</option>
                        <option value="0" @if (!empty($default_watermark) && $default_watermark->status == 0) selected @endif>DISABLE</option>
                        <option value="1" @if (!empty($default_watermark) && $default_watermark->status == 1) selected @endif>ENABLE</option>
                    </select>
                    @error('default_watermark.status')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td width="30%">User Watermark</td>
                <td colspan="2">
                    <select name="own_watermark.status"
                        wire:change="store($event.target.value,'own_watermark.{{ $plan->id }}.status')"
                        id="own_watermark.status" wire:model.defer="own_watermark.{{ $plan->id }}.status"
                        class="form-control">
                        <option value="">STATUS</option>
                        <option value="0" @if (!empty($own_watermark) && $own_watermark->status == 0) selected @endif>DISABLE</option>
                        <option value="1" @if (!empty($own_watermark) && $own_watermark->status == 1) selected @endif>ENABLE</option>
                    </select>
                    @error('own_watermark.status')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td width="30%">Price</td>
                <td colspan="2">
                    <input type="checkbox" wire:click="store($event.target.value,'price.{{ $plan->id }}.values')"
                        @if ($plan->price == '0') checked @endif name="price.value"
                        wire:model.defer="price.{{ $plan->id }}.value" value="0" id="">
                    FREE<br>
                    <input type="number" wire:change="store($event.target.value,'price.{{ $plan->id }}.values')"
                        @if ($plan->price > 0) value="{{ $plan->price }}" @endif
                        wire:keyup="store($event.target.value,'price.{{ $plan->id }}.values')" name="price.value"
                        wire:model.defer="price.{{ $plan->id }}.values" placeholder="$10" class="form-control"
                        id="price.value">
                    @error('price.value')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </td>
            </tr>

        </tbody>
    </table>
</div>
