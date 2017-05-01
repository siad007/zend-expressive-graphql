<?php

namespace Siad007\ZendExpressive\GraphQL\Request;

use Psr\Http\Message\ServerRequestInterface;

class BatchParser implements ParserInterface
{
    const PARAM_ID = 'id';
    private static $queriesDefaultValue = [
        self::PARAM_ID => null,
        self::PARAM_QUERY => null,
        self::PARAM_VARIABLES => null,
    ];

    /**
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function parse(ServerRequestInterface $request)
    {
        // Extracts the GraphQL request parameters
        $queries = $this->getParsedBody($request);
        if (empty($queries)) {
            throw new BadRequestHttpException('Must provide at least one valid query.');
        }
        foreach ($queries as $i => &$query) {
            $query = array_filter($query) + self::$queriesDefaultValue;
            if (!is_string($query[static::PARAM_QUERY])) {
                throw new BadRequestHttpException(sprintf('%s is not a valid query', json_encode($query[static::PARAM_QUERY])));
            }
        }
        return $queries;
    }

    /**
     * Gets the body from the request.
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    private function getParsedBody(ServerRequestInterface $request)
    {
        $type = explode(';', $request->getHeader('content-type'))[0];
        // JSON object
        if ($type !== static::CONTENT_TYPE_JSON) {
            throw new BadRequestHttpException(sprintf('Only request with content type "%s" is accepted.', static::CONTENT_TYPE_JSON));
        }
        $parsedBody = json_decode($request->getBody(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestHttpException('POST body sent invalid JSON');
        }
        return $parsedBody;
    }
}
