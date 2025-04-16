<?php

/**
 *
 * @OA\Post(
 *      path="/api/v1/addAdvertisementFavorite",
 *      operationId="addAdvertisementFavorite",
 *      tags={"AdvertisementFavorite"},
 *      description="Favorite advertisement  adding.",
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
 *       @OA\Response(
 *          response=200,
 *          description="Favorite advertisement was added.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="add_advertisement_favorite")
 *                     ),
 *                  ),
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
 *      path="/api/v1/getAdvertisementFavoriteList",
 *      operationId="getAdvertisementFavoriteList",
 *      tags={"AdvertisementFavorite"},
 *      description="get advertisement favorite list. Added pagination and filters by campaign, private person and category",
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
 *       @OA\Parameter(
 *          name="number_items_page",
 *          description="Number items page for pagination. Defalt 10",
 *          example="10",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="page",
 *          description="Number page for pagination.",
 *          example="2",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="category_id",
 *          description="Category id.",
 *          example="2",
 *          required=false,
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
 *                      @OA\Property(property="current_page", type="integer", example="1"),
 *
 *                      @OA\Property(property="data", type="array",
 *                          @OA\Items(type="object",
 *                              @OA\Property(property="advertisement_favorite_id", type="integer", example="150"),
 *                              @OA\Property(property="advertisement_id", type="integer", example="150"),
 *                              @OA\Property(property="user_id", type="integer", example="15"),
 *                              @OA\Property(property="company_id", type="integer", nullable=true, example="10"),
 *                              @OA\Property(property="title", type="string", example="Размещение рекламы на моторной лодке"),
 *                              @OA\Property(property="price", type="float", example="2500"),
 *                              @OA\Property(property="currency", type="string", example="RUB"),
 *                              @OA\Property(property="city", type="string", example="Санкт-Петербург"),
 *                              @OA\Property(property="city_ext_code", type="string", example=""),
 *                              @OA\Property(property="image", type="string", example="http://advonback/storage/advertisements/old/May2021/JLxIKELXYhnCkYkdiFRq.jpg"),
 *                              @OA\Property(property="payment_name", type="string", example="в месяц"),
 *                              @OA\Property(property="company_name", type="string", example=""),
 *
 *                          ),
 *                      ),
  *                      @OA\Property(property="total", type="integer", example="2"),
 *                  ),
 *              ),
 *          ),
 *       ),
 * )
 *
 */

/**
 *
 * @OA\Delete (
 *      path="/api/v1/deleteAdvertisementsFavorite",
 *      operationId="deleteAdvertisementsFavorite",
 *      tags={"AdvertisementFavorite"},
 *      description="Favorite advertisement  adding.",
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
 *          name="advertisement_favorite_ids",
 *          description="Advertisement favorite ids.",
 *          example="[1, 2]",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="integer")
 *          )
 *       ),
 *       @OA\Response(
 *          response=200,
 *          description="Favorite advertisement was added.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="favorite_advertisements_was_not_delete")
 *                     ),
 *                  ),
 *              ),
 *          ),
 *       ),
 *
 * )
 *
 */

