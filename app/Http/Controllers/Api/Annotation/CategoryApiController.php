<?php

/**
 *
 * @OA\Get(
 *      path="/api/v1/getCategoryList",
 *      operationId="getCategoryList",
 *      tags={"Category"},
 *      description="Getting a list of categories on the main page.",
 *     @OA\Parameter(
 *          name="locale",
 *          description="locale.",
 *          example="ru",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="type",
 *          description="Category type.",
 *          example="performer",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[performer,employer]"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Store user.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                   @OA\Items(type="object",
 *                       @OA\Property(property="category_id", type="integer", example=1),
 *                       @OA\Property(property="photo_url", type="string", example=""),
 *                       @OA\Property(property="category_name", type="string", example="Личные вещи"),
 *                   ),
 *               ),
 *          ),
 *       ),
 *       @OA\Response(response=404, description="Not Found"),
 *       @OA\Response(response=422, description="Error: HTTP_UNPROCESSABLE_ENTITY. Validation errors",
 *          @OA\JsonContent( type="object",
 *              @OA\Property(property="type", type="array",
 *                  @OA\Items(type="string", example="The type field is required.")
 *              ),
 *          ),
 *       ),
 * )
 *
 * @OA\Get(
 *      path="/api/v1/getCategoryListForAdvertisement",
 *      operationId="getCategoryListForAdvertisement",
 *      tags={"Category"},
 *      description="Getting a list of categories for creating ads. The choice of a category for filing a demand for advertising (employer) about public transport is available only for users who have a company.",
 *      @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=false,
 *         description="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9jYXJcL2FwaVwvbG9naW5TZWxsZXIiLCJpYXQiOjE2MTU4MzYzMDksImV4cCI6MTYxNTkyMjcwOSwibmJmIjoxNjE1ODM2MzA5LCJqdGkiOiJxZkNPYmwxUUU2U0lxSldaIiwic3ViIjoxMywicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.BhzZTfcbp5cmFcAjz5b_fw6UpghUdOwrTG_O0ASodkQ",
 *         @OA\SecurityScheme(
 *              securityScheme="bearerAuth",
 *              in="header",
 *              name="Authorization",
 *              type="apiKey",
 *              scheme="Bearer",
 *              bearerFormat="JWT",
 *           ),
 *       ),
 *     @OA\Parameter(
 *          name="locale",
 *          description="locale.",
 *          example="ru",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="type",
 *          description="Category type.",
 *          example="performer",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[performer,employer]"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Store user.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                   @OA\Items(type="object",
 *                       @OA\Property(property="category_id", type="integer", example=1),
 *                       @OA\Property(property="photo_url", type="string", example=""),
 *                       @OA\Property(property="category_name", type="string", example="Личные вещи"),
 *                   ),
 *               ),
 *          ),
 *       ),
 *       @OA\Response(response=404, description="Not Found"),
 *       @OA\Response(response=422, description="Error: HTTP_UNPROCESSABLE_ENTITY. Validation errors",
 *          @OA\JsonContent( type="object",
 *              @OA\Property(property="type", type="array",
 *                  @OA\Items(type="string", example="TThe type field is required.")
 *              ),
 *          ),
 *       ),
 * )
 *
 * @OA\Get(
 *      path="/api/v1/getChildCategoryList",
 *      operationId="getChildCategoryList",
 *      tags={"Category"},
 *      description="получение категорий 2 уровня используется на главнойb и для создание объевлений",
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
 *      @OA\Parameter(
 *          name="category_id",
 *          description="Category id.",
 *          example="1",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Get child category list.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="category", type="object",
 *                      @OA\Property(property="category_id", type="integer", example=1),
 *                      @OA\Property(property="photo_url", type="string", example=""),
 *                      @OA\Property(property="category_name", type="string", example="Личные вещи"),
 *                  ),
 *                  @OA\Property(property="child_categories", type="array",
 *                      @OA\Items(type="object",
 *                          @OA\Property(property="child_category_id", type="integer", example=1),
 *                           @OA\Property(property="child_category_name", type="string", example="Одежда"),
 *                      ),
 *                  ),
 *              ),
 *          ),
 *       ),
 *       @OA\Response(response=404, description="Not Found"),
 *       @OA\Response(response=422, description="Error: HTTP_UNPROCESSABLE_ENTITY. Validation errors",
 *          @OA\JsonContent( type="object",
 *              @OA\Property(property="category_id", type="array",
 *                  @OA\Items(type="string", example="TThe category id field is required.")
 *              ),
 *          ),
 *       ),
 * )
 *
 *
 * @OA\Get(
 *      path="/api/v1/getCategoryFormFields",
 *      operationId="getCategoryFormFields",
 *      tags={"Category"},
 *      description="получение динамических полей для категории при создании объявления необходима доработка",
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
 *      @OA\Parameter(
 *          name="category_id",
 *          description="Category id.",
 *          example="1",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Store user.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                   @OA\Items(type="object",
 *                       @OA\Property(property="form_field_id", type="integer", example=1),
 *                       @OA\Property(property="form_field_name", type="string", example="Размеры"),
 *                   ),
 *               ),
 *          ),
 *       ),
 *       @OA\Response(response=404, description="Not Found"),
 *       @OA\Response(response=422, description="Error: HTTP_UNPROCESSABLE_ENTITY. Validation errors",
 *          @OA\JsonContent( type="object",
 *              @OA\Property(property="category_id", type="array",
 *                  @OA\Items(type="string", example="TThe category id field is required.")
 *              ),
 *          ),
 *       ),
 * )
 *
 */
