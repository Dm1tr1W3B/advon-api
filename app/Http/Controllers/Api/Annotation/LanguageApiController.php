<?php


/**
 *
 * @OA\Get(
 *      path="/api/v1/getLanguages",
 *      operationId="getLanguages",
 *      tags={"Language"},
 *      description="Get a list of languages",
 *     @OA\Parameter(
 *          name="locale",
 *          description="locale.",
 *          example="en",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="List of languages",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                          @OA\Items(type="object",
 *                              @OA\Property(property="key", type="string",  example="ru"),
 *                              @OA\Property(property="name", type="string", example="Русский"),
 *                              @OA\Property(property="rtl", type="boolean", example=false),
 *                              @OA\Property(property="image", type="string",nullable=true, example=null),
 *                  ),
 *                    @OA\Property(property="locale", type="string",example="ru")
 *              ),
 *          ),
 *    ),
 *
 * @OA\Response(response=404, description="Not Found"),

 *
 * )
 *
 */

/**
 *
 * @OA\Get(
 *      path="/api/v1/getTranslations",
 *      operationId="getTranslations",
 *      tags={"Language"},
 *      description="Get translations by keys",
 *     @OA\Parameter(
 *          name="language_key",
 *          description="language key.",
 *          example="en",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *       @OA\Parameter(
 *          name="keys[]",
 *          description="Array of key",
 *          example="Some key",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="string")
 *
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="List of languages",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="price", type="string",  example="Цена"),
 *                  @OA\Property(property="payment", type="string",  example="Оплата"),
 *                  @OA\Property(property="some_key", type="string",  example="key translation"),
 *              ),
 *          ),
 *    ),
 *
 *    @OA\Response(response=404, description="Not Found"),
 *
 * )
 *
 */
