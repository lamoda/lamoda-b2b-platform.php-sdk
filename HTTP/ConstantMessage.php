<?php
namespace LamodaB2B\HTTP;

class ConstantMessage
{
    const FAILED_TO_GET_TOKEN  = 'Failed to get token';
    const HTTP_REQUEST_SUCCESS = 'HTTP request success';
    const HTTP_REQUEST_FAILED  = 'HTTP request failed';
    const HTTP_REQUEST_ERROR   = 'HTTP request error';

    const MISSING_AUTH_PARAMETER = 'Missing auth parameter %s for partner %s';
    const FAILED_TO_GET_TRACKING = 'Failed to get tracking';
    const CLIENT_ERROR_TRACKING  = 'There was a client error';
    const TRACKING_IS_NOT_FOUND  = 'Tracking is not found';

    const UNEXPECTED_STRUCTURE_RESPONCE = 'Unexpected structure responce';
}