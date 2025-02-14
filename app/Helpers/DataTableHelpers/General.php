<?php

namespace App\Helpers\DataTableHelpers;

class General
{
    public static function bulkCheckbox($id)
    {
        return <<<HTML
<div class="bulk-checkbox-wrapper">
    <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{$id}">
</div>
HTML;

    }

    public static function image($image_id)
    {
        return render_attachment_preview_for_admin($image_id);
    }

    public static function deletePopover($url)
    {
        $token = csrf_token();

        return <<<HTML
<a tabindex="0" class="btn btn-sm btn-danger btn-xs mb-2 me-1 swal_delete_button">
    <i class="ti-trash"></i>
</a>
<form method='post' action='{$url}' class="d-none">
<input type='hidden' name='_token' value='{$token}'>
<br>
<button type="submit" class="swal_form_submit_btn d-none"></button>
 </form>
HTML;

    }

    public static function editIcon($url)
    {
        return <<<HTML
<a class="btn btn-primary btn-xs mb-2 me-1" href="{$url}">
    <i class="ti-pencil"></i>
</a>
HTML;

    }

    public static function viewIcon($url)
    {
        return <<<HTML
<a class="btn btn-info btn-xs mb-2 me-1" target="_blank" href="{$url}">
    <i class="ti-eye"></i>
</a>
HTML;

    }

    public static function cloneIcon($action, $id)
    {
        $csrf = csrf_field();

        return <<<HTML
<form action="{$action}" method="post" class="d-inline">
{$csrf}
    <input type="hidden" name="item_id" value="{$id}">
    <button type="submit" title="clone this to new draft" class="btn btn-xs btn-secondary btn-sm mb-2 me-1"><i class="far fa-copy"></i></button>
</form>
HTML;

    }

    public static function statusSpan($status)
    {
        $output = '';

        if ($status === 'draft') {
            $output .= '<span class="alert alert-primary" >'.__('Draft').'</span>';
        } elseif ($status === 'archive') {
            $output .= '<span class="alert alert-warning" >'.__('Archive').'</span>';
        } elseif ($status === 'pending') {
            $output .= '<span class="alert alert-warning" >'.__('Pending').'</span>';
        } elseif ($status === 'complete') {
            $output .= '<span class="alert alert-success" >'.__('Complete').'</span>';
        } elseif ($status === 'close') {
            $output .= '<span class="alert alert-danger" >'.__('Close').'</span>';
        } elseif ($status === 'in_progress') {
            $output .= '<span class="alert alert-info" >'.__('In Progress').'</span>';
        } elseif ($status === 'publish') {
            $output .= '<span class="alert alert-success" >'.__('Publish').'</span>';
        } elseif ($status === 'approved') {
            $output .= '<span class="alert alert-success" >'.__('Approved').'</span>';
        } elseif ($status === 'confirm') {
            $output .= '<span class="alert alert-success" >'.__('Confirm').'</span>';
        } elseif ($status === 'yes') {
            $output .= '<span class="alert alert-success" >'.__('Yes').'</span>';
        } elseif ($status === 'no') {
            $output .= '<span class="alert alert-danger" >'.__('No').'</span>';
        } elseif ($status === 'cancel') {
            $output .= '<span class="alert alert-danger" >'.__('Cancel').'</span>';
        }

        return $output;
    }

    public static function paymentAccept($url)
    {
        $token = csrf_token();

        return <<<HTML
<a tabindex="0" class="btn btn-success btn-xs mb-2 me-1 swal_change_approve_payment_button">
    <i class="ti-check"></i>
</a>
<form method='post' action='{$url}' class="d-none">
    <input type='hidden' name='_token' value='{$token}'>
    <br>
    <button type="submit" class="swal_form_submit_btn d-none"></button>
</form>

HTML;

    }

    public static function invoiceBtn($url, $id)
    {
        $csrf = csrf_field();
        $title = __('Invoice');

        return <<<HTML
 <form action="{$url}"  method="post">{$csrf}
    <input type="hidden" name="id" id="invoice_generate_order_field" value="{$id}">
    <button class="btn btn-sm btn-secondary mb-2" type="submit">{$title}</button>
</form>
HTML;

    }

    public static function reminderMail($url, $id)
    {
        $csrf = csrf_field();

        return <<<HTML
<form action="{$url}"  method="post">
{$csrf}
    <input type="hidden" name="id" value="{$id}">
    <button class="btn btn-sm btn-secondary mb-2" type="submit"><i class="fas fa-bell"></i></button>
</form>
HTML;

    }
}//end class
