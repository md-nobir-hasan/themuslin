<?php

namespace App\Helpers;

/**
 * this class will contain all Flash Message helper method
 *
 * */
class FlashMsg
{
    /*------------------------------------------
    *              Create
    *-----------------------------------------*/
    public static function create_succeed(String $item) : array
    {
        return [
            'type' => 'success',
            'msg' => __( ucfirst($item) . " Created Successfully")
        ];
    }

    public static function create_failed(String $item) : array
    {
        return [
            'type' => 'danger',
            'msg' => __( ucfirst($item) . " Create Failed")
        ];
    }

    /*------------------------------------------
    *              Update
    *-----------------------------------------*/
    public static function update_succeed(String $item) : array
    {
        return [
            'type' => 'success',
            'msg' => __( ucfirst($item) . " Updated Successfully")
        ];
    }

    public static function update_failed(String $item) : array
    {
        return [
            'type' => 'danger',
            'msg' => __( ucfirst($item) . " Update Failed")
        ];
    }

    /*------------------------------------------
    *              Delete
    *-----------------------------------------*/
    public static function delete_succeed(String $item) : array
    {
        return [
            'type' => 'danger',
            'msg' => __( ucfirst($item) . " Deleted Successfully")
        ];
    }

    public static function delete_failed(String $item) : array
    {
        return [
            'type' => 'danger',
            'msg' => __( ucfirst($item) . " Delete Failed")
        ];
    }

    /*------------------------------------------
    *              Clone
    *-----------------------------------------*/
    public static function clone_succeed(String $item) : array
    {
        return [
            'type' => 'success',
            'msg' => __( ucfirst($item) . " Cloned Successfully")
        ];
    }

    public static function clone_failed(String $item) : array
    {
        return [
            'type' => 'success',
            'msg' => __( ucfirst($item) . " Cloned Failed")
        ];
    }

    /*------------------------------------------
    *              Signup
    *-----------------------------------------*/
    public static function signup_succeed($item = null) : array
    {
        if ($item && $item === 'teacher') {
            return [
                'type' => 'success',
                'msg' => __("Signup Success. Pending admin approval.")
            ];
        }

        return [
            'type' => 'success',
            'msg' => __("Signup Success")
        ];
    }

    public static function signup_failed() : array
    {
        return [
            'type' => 'danger',
            'msg' => __("Signup Failed")
        ];
    }

    /*------------------------------------------
    *              Teacher approve
    *-----------------------------------------*/
    public static function teacherApproveSucceed() : array
    {
        return [
            'type' => 'success',
            'msg' => __("Teacher approve Success")
        ];
    }

    public static function teacherApproveFailed() : array
    {
        return [
            'type' => 'danger',
            'msg' => __("Teacher approve Failed")
        ];
    }

    /*------------------------------------------
    *              Custom
    *-----------------------------------------*/
    /**
     * @param string $type CSS alert class
     * @param string $msg Message to display
     */
    public static function explain($type, $msg) : array
    {
        return [
            'type' => $type ?? 'danger',
            'msg' => __($msg)
        ];
    }

    /*------------------------------------------
    *              Prev - f($msg)
    *-----------------------------------------*/
    public static function item_cloned($msg = null)
    {
        return [
            'type' => 'success',
            'msg' => $msg ?? __('Item Cloned Successfully')
        ];
    }
    public static function item_new($msg = null)
    {
        return [
            'type' => 'success',
            'msg' => $msg ?? __('Item Added Successfully')
        ];
    }
    public static function item_update($msg = null)
    {
        return [
            'type' => 'success',
            'msg' => $msg ?? __('Item Updated Successfully')
        ];
    }
    public static function item_delete($msg = null)
    {
        return [
            'type' => 'danger',
            'msg' => $msg ?? __('Item Deleted Successfully')
        ];
    }
    public static function item_clone($msg = null)
    {
        return [
            'type' => 'success',
            'msg' => $msg ?? __('Item Cloned Successfully')
        ];
    }
    public static function settings_update($msg = null)
    {
        return [
            'type' => 'success',
            'msg' => $msg ?? __('Settings Updated Successfully')
        ];
    }
    public static function settings_new($msg = null)
    {
        return [
            'type' => 'success',
            'msg' => $msg ?? __('Settings Added Successfully')
        ];
    }
    public static function settings_delete($msg = null)
    {
        return [
            'type' => 'danger',
            'msg' => $msg ?? __('Settings Deleted Successfully')
        ];
    }
    public static function restore_succeed(String $item) : array
    {
        return [
            'type' => 'success',
            'msg' => __( ucfirst($item) . " Restore Successfully")
        ];
    }

    public static function restore_failed(String $item) : array
    {
        return [
            'type' => 'danger',
            'msg' => __( ucfirst($item) . " Restore Failed")
        ];
    }

}
