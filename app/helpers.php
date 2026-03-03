<?php
function isBuilder(): bool
{
    return request()->is('admin/builder/*')
        || request()->is('preview/*');
}