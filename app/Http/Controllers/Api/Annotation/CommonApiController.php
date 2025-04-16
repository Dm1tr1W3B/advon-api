<?php

/**
 *
 * @OA\Post(
 *      path="/api/v1/userSendSmsKey",
 *      operationId="userSendSmsKey",
 *      tags={"SMS"},
 *      description="Sending an SMS with a code to the user..",
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
 *          name="type",
 *          description="SMS type.",
 *          example="user_verification",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[user_verification,password_recovery]"
 *          )
 *       ),
 *       @OA\Response(
 *          response=200,
 *          description="Successfully send SMS.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="successfully_send_sms_kye")
 *                     ),
 *                  ),
 *              ),
 *          ),
 *       ),
 * )
 *
 */

/**
 *
 * @OA\Post(
 *      path="/api/v1/userCheckSmsKey",
 *      operationId="userCheckSmsKey",
 *      tags={"SMS"},
 *      description="Check SMS key.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=true,
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
 *      @OA\Parameter(
 *          name="sms_key",
 *          description="SMS key.",
 *          example="1234",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer",
 *              maxLength=4,
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="type",
 *          description="SMS type.",
 *          example="user_verification",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[user_verification,password_recovery]"
 *          )
 *       ),
 *       @OA\Response(
 *          response=200,
 *          description="SMS kye is valid.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="sms_kye_is_valid")
 *                     ),
 *                  ),
 *              ),
 *          ),
 *       ),
 *       @OA\Response(response=422, description="Error: HTTP_UNPROCESSABLE_ENTITY. Validation errors",
 *          @OA\JsonContent( type="object",
 *              @OA\Property(property="sms_key", type="array",
 *                  @OA\Items(type="string", example="TThe sms_key field is required.")
 *              ),
 *          ),
 *       ),
 * )
 *
 */

/**
 *
 * @OA\Get(
 *      path="/api/v1/getActiveCurrencies",
 *      operationId="getActiveCurrencies",
 *      tags={"Common"},
 *      description="Get active currencies",
 *       @OA\Response(
 *          response=200,
 *          description="Get active currencies",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="currency_id", type="integer",  example="1"),
 *                      @OA\Property(property="translation_currency_code", type="string", example="руб"),
 *                  ),
  *              ),
 *          ),
 *    ),
 *
 *    @OA\Response(response=404, description="Not Found"),
 *
 * )
 *
 */

/**
 *
 * @OA\Get(
 *      path="/api/v1/getContacts",
 *      operationId="getContacts",
 *      tags={"Common"},
 *      description="Get Contacts",
 *       @OA\Response(
 *          response=200,
 *          description="Get Contacts",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                          @OA\Items(type="object",
 *                              @OA\Property(property="id", type="integer",  example="1"),
 *                              @OA\Property(property="name", type="string", example="Telegram"),
 *                  ),
 *
 *              ),
 *          ),
 *    ),
 *
 * @OA\Response(response=404, description="Not Found"),
 *
 * )
 *
 */


