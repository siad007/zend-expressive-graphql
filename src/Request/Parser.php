<?php

namespace Siad007\ZendExpressive\GraphQL\Request;

use Psr\Http\Message\ServerRequestInterface;

class Parser implements ParserInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function parse(ServerRequestInterface $request)
    {
        // Extracts the GraphQL request parameters
        $parsedBody = $this->getParsedBody($request);

        return $this->getParams($request, $parsedBody);
    }

    /**
     * Gets the body from the request based on Content-Type header.
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    private function getParsedBody(ServerRequestInterface $request)
    {
        $body = $request->getBody();
        $type = explode(';', $request->getHeader('content-type'))[0];
        switch ($type) {
            // Plain string
            case static::CONTENT_TYPE_GRAPHQL:
                $parsedBody = [static::PARAM_QUERY => $body];
                break;
            // JSON object
            case static::CONTENT_TYPE_JSON:
                if ($body === '') {
                    throw new BadRequestHttpException('The request content body must not be empty when using json content type request.');
                }
                $parsedBody = json_decode($body, true);
                if (JSON_ERROR_NONE !== json_last_error()) {
                    throw new BadRequestHttpException('POST body sent invalid JSON');
                }
                break;
            // URL-encoded query-string
            case static::CONTENT_TYPE_FORM:
            case static::CONTENT_TYPE_FORM_DATA:
                $parsedBody = $request->getParsedBody();
                break;
            default:
                $parsedBody = [];
                break;
        }
        return $parsedBody;
    }

    /**
     * Gets the GraphQL parameters from the request.
     *
     * @param ServerRequestInterface $request
     * @param array $data
     *
     * @return array
     */
    private function getParams(ServerRequestInterface $request, array $data = [])
    {
        // Add default request parameters
        $data = array_filter($data) + [
                static::PARAM_QUERY => null,
                static::PARAM_VARIABLES => null,
                static::PARAM_OPERATION_NAME => null,
            ];
        // Keep a reference to the query-string
        $qs = $request->getQueryParams();
        // Override request using query-string parameters
        $query = isset($qs[static::PARAM_QUERY]) ? $qs[static::PARAM_QUERY] : $data[static::PARAM_QUERY];
        $variables = isset($qs[static::PARAM_VARIABLES]) ? $qs[static::PARAM_VARIABLES] : $data[static::PARAM_VARIABLES];
        $operationName = isset($qs[static::PARAM_OPERATION_NAME]) ? $qs[static::PARAM_OPERATION_NAME] : $data[static::PARAM_OPERATION_NAME];
        // `query` parameter is mandatory.
        if (empty($query)) {
            throw new BadRequestHttpException('Must provide query parameter');
        }
        // Variables can be defined using a JSON-encoded object.
        // If the parsing fails, an exception will be thrown.
        if (is_string($variables)) {
            $variables = json_decode($variables, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new BadRequestHttpException('Variables are invalid JSON');
            }
        }
        return [
            static::PARAM_QUERY => $query,
            static::PARAM_VARIABLES => $variables,
            static::PARAM_OPERATION_NAME => $operationName,
        ];
    }
}
