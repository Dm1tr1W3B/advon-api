<?php

/**
 *
 * @OA\Post(
 *      path="/api/v1/storeAdvertisementComment",
 *      operationId="storeAdvertisementComment",
 *      tags={"AdvertisementComment"},
 *      description="Store advertisement comment.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=true,
 *         description="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hZHZvbmJhY2tcL2FwaVwvdjFcL3N0b3JlVXNlciIsImlhdCI6MTYxODkyMzYyOSwiZXhwIjoxNjE5MDA3NjI5LCJuYmYiOjE2MTg5MjM2MjksImp0aSI6ImRIYkJTOGVUbFExbTdNc00iLCJzdWIiOjQsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.oz3BsCW2qqtYhQx9dA-4qREBF4qw1WLBjra_fMPEO2Y",
 *         @OA\SecurityScheme(
 *              securityScheme="bearerAuth",
 *              in="header",
 *              name="Authorization",
 *              type="apiKey",
 *              scheme="Bearer",
 *              bearerFormat="JWT",
 *           ),
 *       ),
 *      @OA\Parameter(
 *          name="locale",
 *          description="locale.",
 *          example="en",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="advertisement_id",
 *          description="Advertisement id.",
 *          example="150",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="message",
 *          description="message.",
 *          example="Tect message",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Advertisement comment  was added.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="message", type="string", example="add_advertisement_comment"),
 *              ),
 *          ),
 *       ),
 *
 * )
 *
 */

/**
 *
 * @OA\Get (
 *      path="/api/v1/getAdvertisementCommentList",
 *      operationId="getAdvertisementCommentList",
 *      tags={"AdvertisementComment"},
 *      description="Get advertisement comments ",
  *      @OA\Parameter(
 *          name="locale",
 *          description="locale.",
 *          example="en",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="advertisement_id",
 *          description="Advertisement id.",
 *          example="150",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *       ),
 *       @OA\Response(
 *          response=200,
 *          description="Get list.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="name", type="string", example="user name"),
 *                      @OA\Property(property="avatar", type="string", example="http://advonback/storage/users/default.png"),
 *                      @OA\Property(property="created_at", type="string", example="18.06.2021"),
 *                      @OA\Property(property="message", type="string", example="message"),
 *
 *                  ),
 *              ),
 *          ),
 *       ),
 * )
 *
 */
