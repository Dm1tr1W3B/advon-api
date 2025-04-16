<?php

/**
 *
 * @OA\Post(
 *      path="/api/v1/storeAdvertisement",
 *      operationId="storeAdvertisement",
 *      tags={"Advertisement"},
 *      description="Store advertisement.",
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
 *          name="advertisement_type",
 *          description="Advertisement type. performer or employer ",
 *          example="none",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[performer, employer]"
 *          )
 *       ),
 *       @OA\Parameter(
 *          name="person_type",
 *          description="Advertisement person type. private person or company",
 *          example="none",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[private_person, company]"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="category_id",
 *          description="Category id.",
 *          example="150",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="child_category_id",
 *          description="Child category id.",
 *          example="150",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="title",
 *          description="Title.",
 *          example="Advertisement title",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *               maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="description",
 *          description="Description.",
 *          example="Advertisement description",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="price_type",
 *          description="Price type. 0 bargaining is possible not selected, 1 bargaining is possible selected, 8 Negotiated ",
 *          example="1",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 8]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="currency_id",
 *          description="Currency id. Default currency rub (id = 1)",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="video_url",
 *          description="Video url.",
 *          example="https://api.advon.test.ut.in.ua/",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="country",
 *          description="Country.",
 *          example="Country",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="region",
 *          description="Region.",
 *          example="Region",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="city",
 *          description="City.",
 *          example="city",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="country_ext_code",
 *          description="Country ext code.",
 *          example="country_ext_code",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="region_ext_code",
 *          description="Region ext code.",
 *          example="region_ext_code",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="city_ext_code",
 *          description="City ext code.",
 *          example="city_ext_code",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="latitude",
 *          description="Latitude",
 *          example="41.40338",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="longitude",
 *          description="Longitude",
 *          example="41.40338",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="images[]",
 *          description="Array of images",
 *          example="Some iamges",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="image")
 *
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="price",
 *          description="Price",
 *          example="43.00",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *    @OA\Parameter(
 *          name="payment",
 *          description="Payment. 0 not used 1 per day, 2 per week, 3 per month, 4 per year, 5 for 20 years, 6 forever",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2, 3, 4, 5, 6]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="hashtags[]",
 *          description="Array of hashtags",
 *          example="Some hashtags",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="string")
 *
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="reach_audience",
 *          description="Reach audience",
 *          example="130",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="travel_abroad",
 *          description="Travel abroad. 0 not used 1 no 2 yes",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="ready_for_political_advertising",
 *          description="Ready for political advertising. 0 not used 1 no 2 yes",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="photo_report",
 *          description="Photo report. 0 not used 1 no 2 yes",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="make_and_place_advertising",
 *          description="Make and place advertising. 0 not used 1 no 2 yes",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2]"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="amount",
 *          description="Amount",
 *          example="130",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="length",
 *          description="Length",
 *          example="130.00",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="width",
 *          description="Width",
 *          example="130.00",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="video",
 *          description="Video.",
 *          example="https://api.advon.test.ut.in.ua/",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="deadline_date",
 *          description="Deadline date. Unix Timestamp.",
 *          example="1622722862",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="sample",
 *          description="Sample.",
 *          example="file mp3",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="link_page",
 *          description="Link page.",
 *          example="https://api.advon.test.ut.in.ua/",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="date_of_the",
 *          description="Date of the. Unix Timestamp.",
 *          example="1622722862",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="date_start",
 *          description="Date start. Unix Timestamp.",
 *          example="1622722862",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="date_finish",
 *          description="Date finish. Unix Timestamp.",
 *          example="1622722862",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Advertisement was added.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="message", type="string", example="store_advertisement")
 *              ),
 *          ),
 *      ),
 * )
 *
 */


/**
 *
 * @OA\Post (
 *      path="/api/v1/updateAdvertisement",
 *      operationId="updateAdvertisement",
 *      tags={"Advertisement"},
 *      description="Store advertisement.",
 *     @OA\Parameter(
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
 *     @OA\Parameter(
 *          name="title",
 *          description="Title.",
 *          example="Advertisement title edit",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *               maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="description",
 *          description="Description.",
 *          example="Advertisement description edit",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="price_type",
 *          description="Price type. 0 bargaining is possible not selected, 1 bargaining is possible selected, 8 Negotiated ",
 *          example="1",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 8]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="currency_id",
 *          description="Currency id. Default currency rub (id = 1)",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="video_url",
 *          description="Video url.",
 *          example="https://api.advon.test.ut.in.ua/",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="is_published",
 *          description="Authorization published.",
 *          example=false,
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="boolean"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="country",
 *          description="Country.",
 *          example="Country",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="region",
 *          description="Region.",
 *          example="Region",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="city",
 *          description="City.",
 *          example="city",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="country_ext_code",
 *          description="Country ext code.",
 *          example="country_ext_code",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="region_ext_code",
 *          description="Region ext code.",
 *          example="region_ext_code",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="city_ext_code",
 *          description="City ext code.",
 *          example="city_ext_code",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="latitude",
 *          description="Latitude",
 *          example="41.40338",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="longitude",
 *          description="Longitude",
 *          example="41.40338",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="images[]",
 *          description="Array of images",
 *          example="Some iamges",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="image")
 *
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="price",
 *          description="Price",
 *          example="43.00",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *    @OA\Parameter(
 *          name="payment",
 *          description="Payment. 0 not used 1 per day, 2 per week, 3 per month, 4 per year, 5 for 20 years, 6 forever",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2, 3, 4, 5, 6]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="hashtags[]",
 *          description="Array of hashtags",
 *          example="Some hashtags",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="string")
 *
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="reach_audience",
 *          description="Reach audience",
 *          example="130",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="travel_abroad",
 *          description="Travel abroad. 0 not used 1 no 2 yes",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="ready_for_political_advertising",
 *          description="Ready for political advertising. 0 not used 1 no 2 yes",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="photo_report",
 *          description="Photo report. 0 not used 1 no 2 yes",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2]"
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="make_and_place_advertising",
 *          description="Make and place advertising. 0 not used 1 no 2 yes",
 *          example="1",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[0, 1, 2]"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="amount",
 *          description="Amount",
 *          example="130",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="length",
 *          description="Length",
 *          example="130.00",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="width",
 *          description="Width",
 *          example="130.00",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="numeric"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="video",
 *          description="Video.",
 *          example="https://api.advon.test.ut.in.ua/",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="deadline_date",
 *          description="Deadline date. Unix Timestamp.",
 *          example="1622722862",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="sample",
 *          description="Sample.",
 *          example="file mp3",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="link_page",
 *          description="Link page.",
 *          example="https://api.advon.test.ut.in.ua/",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *       ),
 *     @OA\Parameter(
 *          name="date_of_the",
 *          description="Date of the. Unix Timestamp.",
 *          example="1622722862",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="date_start",
 *          description="Date start. Unix Timestamp.",
 *          example="1622722862",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="date_finish",
 *          description="Date finish. Unix Timestamp.",
 *          example="1622722862",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Advertisement was update.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="message", type="string", example="store_advertisement")
 *              ),
 *          ),
 *       ),
 * )
 *
 */

/**
 *
 * @OA\Patch (
 *      path="/api/v1/setPublished",
 *      operationId="setPublished",
 *      tags={"Advertisement"},
 *      description="Activation and deactivation of activation. In the old project, publish. Activation is possible only with a verified phone. The user and the company have a phone check.",
 *     @OA\Parameter(
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
 *     @OA\Parameter(
 *          name="advertisement_ids[]",
 *          description="Array of advertisement ids",
 *          example="Some advertisement ids",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="integer")
 *
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="is_published",
 *          description="Is published.",
 *          example=false,
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="boolean"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Set is_published and published_at.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="update_advertisement")
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
 * @OA\Patch (
 *      path="/api/v1/setHide",
 *      operationId="setHide",
 *      tags={"Advertisement"},
 *      description="Advertisement set hide.",
 *     @OA\Parameter(
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
 *     @OA\Parameter(
 *          name="advertisement_ids[]",
 *          description="Array of advertisement ids",
 *          example="Some advertisement ids",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="integer")
 *
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="is_hide",
 *          description="Is hide.",
 *          example=false,
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="boolean"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Set is_hide.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="update_advertisement")
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
 * @OA\Delete  (
 *      path="/api/v1/deleteAdvertisement",
 *      operationId="deleteAdvertisement",
 *      tags={"Advertisement"},
 *      description="Delete advertisement.",
 *     @OA\Parameter(
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
 *     @OA\Parameter(
 *          name="advertisement_ids[]",
 *          description="Array of advertisement ids",
 *          example="Some advertisement ids",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="integer")
 *
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="delete Advertisement.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="delete_advertisement")
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
 * @OA\Get (
 *      path="/api/v1/getAdvertisementListForUser",
 *      operationId="getAdvertisementListForUser",
 *      tags={"Advertisement"},
 *      description="get advertisement list. Added pagination and filters by active, search and category",
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
 *      @OA\Parameter(
 *          name="search",
 *          description="search by title, description and hashtags.",
 *          example="2",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *       ),
 *       @OA\Parameter(
 *          name="advertisement_type",
 *          description="Advertisement type. performer or employer ",
 *          example="none",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="enum:[performer, employer]"
 *          )
 *       ),
 *      @OA\Parameter(
 *          name="is_published",
 *          description="Authorization published.",
 *          example=false,
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="boolean"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Get list.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *
 *                          @OA\Property(property="advertisementList", type="object",
 *                              @OA\Property(property="current_page", type="integer", example="1"),
 *                              @OA\Property(property="data", type="array",
 *                                  @OA\Items(type="object",
 *                                      @OA\Property(property="advertisement_id", type="integer", example="150"),
 *                                      @OA\Property(property="title", type="string", example="title"),
 *                                      @OA\Property(property="category_id", type="integer", example="12"),
 *                                      @OA\Property(property="price", type="float", example="2500"),
 *                                      @OA\Property(property="number_views", type="integer", example="12"),
 *                                      @OA\Property(property="number_contacts", type="integer", example="13"),
 *                                      @OA\Property(property="currency", type="string", example="RUB"),
 *                                      @OA\Property(property="is_hide", type="boolean", example="false"),
 *                                      @OA\Property(property="number_messages", type="integer", example="13"),
 *                                      @OA\Property(property="date_start", type="string", example="25.07.2019"),
 *                                      @OA\Property(property="date_finish", type="string", example="25.08.2019"),
 *                                      @OA\Property(property="category_name", type="string", example="Личные вещи"),
 *                                  ),
 *                              ),
 *                              @OA\Property(property="total", type="integer", example="2"),
 *                          ),
 *                           @OA\Property(property="categories", type="array",
 *                               @OA\Items(type="object",
 *                                  @OA\Property(property="category_id", type="integer", example=1),
 *                                  @OA\Property(property="photo_url", type="string", example=""),
 *                                  @OA\Property(property="category_name", type="string", example="Личные вещи"),
 *                               ),
 *                           ),
 *                           @OA\Property(property="number_active", type="integer", example="15"),
 *                           @OA\Property(property="number_passive", type="integer", example="5"),
 *
 *                  ),
 *              ),
 *          ),
 *       ),
 * )
 *
 */

/**
 *
 * @OA\Get(
 *      path="/api/v1/editAdvertisement",
 *      operationId="editAdvertisement",
 *      tags={"Advertisement"},
 *      description="Get advertisement values for update advertisement.",
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
 *      @OA\Response(
 *          response=200,
 *          description="Get Advertisement.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="advertisement_id", type="integer", example="150"),
 *                  @OA\Property(property="advertisement_type", type="string", example="performer"),
 *                  @OA\Property(property="person_type", type="string", example="company"),
 *                  @OA\Property(property="category", type="object",
 *                      @OA\Property(property="category_id", type="integer", example="46"),
 *                      @OA\Property(property="photo_url", type="string", example=""),
 *                      @OA\Property(property="category_name", type="string", example="Медиапроекты"),
 *                  ),
 *                  @OA\Property(property="child_category", type="object",nullable=true,
 *                      @OA\Property(property="child_category_id", type="integer", example="60"),
 *                      @OA\Property(property="child_category_name", type="string", example="Промокампании"),
 *                  ),
 *                  @OA\Property(property="title", type="string", example="title"),
 *                  @OA\Property(property="description", type="string", example="description"),
 *                  @OA\Property(property="latitude", type="number", nullable=true, example=null),
 *                  @OA\Property(property="longitude", type="number", nullable=true, example=null),
 *                  @OA\Property(property="additional_photos", type="array",
 *                      @OA\Items(type="object",
 *                          @OA\Property(property="id", type="integer", example="150"),
 *                          @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                          @OA\Property(property="type", type="string", example="jpg"),
 *                      ),
 *                  ),
 *                  @OA\Property(property="currency", type="object",
 *                      @OA\Property(property="currency_id", type="integer", example="1"),
 *                      @OA\Property(property="code", type="string", example="RUB"),
 *                      @OA\Property(property="name", type="string", example="Российский рубель"),
 *                  ),
 *                  @OA\Property(property="price", type="number", nullable=true, example="12.15"),
 *                  @OA\Property(property="ready_for_political_advertising", type="integer", example="1"),
 *                  @OA\Property(property="hashtags", type="array",
 *                      @OA\Items(type="string", example="hashtag_1"),
 *                      @OA\Items(type="string", example="hashtag_N"),
 *                  ),
 *                 @OA\Property(property="reach_audience", type="integer", example="21"),
 *                 @OA\Property(property="payment", type="integer", example="1"),
 *                 @OA\Property(property="make_and_place_advertising", type="integer", example="2"),
 *                 @OA\Property(property="video", type="string", example="http://advonback/test.mp4"),
 *                 @OA\Property(property="deadline_date", type="string", example="2021-06-04 13:46:33"),
 *                 @OA\Property(property="sample", type="string", example="http://advonback/storage//additional_sample/June2021/Vls58oIv9k5jUo8CyAxHYZAzVyEzMz91uLBPynrk.mp3"),
 *             ),
 *          ),
 *       ),
 * )
 *
 */

/**
 *
 * @OA\Get(
 *      path="/api/v1/showAdvertisement",
 *      operationId="showAdvertisement",
 *      tags={"Advertisement"},
 *      description="Get advertisement values for big card.",
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
 *      @OA\Response(
 *          response=200,
 *          description="Get Advertisement.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="advertisement_id", type="integer", example="150"),
 *                  @OA\Property(property="advertisement_type", type="string", example="performer"),
 *                  @OA\Property(property="person_type", type="string", example="company"),
 *                  @OA\Property(property="title", type="string", example="title"),
 *                  @OA\Property(property="description", type="string", example="description"),
 *                  @OA\Property(property="additional_photos", type="array",
 *                      @OA\Items(type="object",
 *                          @OA\Property(property="id", type="integer", example="150"),
 *                          @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                          @OA\Property(property="type", type="string", example="jpg"),
 *                      ),
 *                  ),
 *                  @OA\Property(property="currency", type="object",
 *                      @OA\Property(property="currency_id", type="integer", example="1"),
 *                      @OA\Property(property="code", type="string", example="RUB"),
 *                      @OA\Property(property="name", type="string", example="Российский рубель"),
 *                  ),
 *                  @OA\Property(property="number_views_all", type="integer", example="15"),
 *                  @OA\Property(property="number_views_day", type="integer", example="1"),
 *                  @OA\Property(property="number_advertisement", type="integer", example="7"),
 *                  @OA\Property(property="person", type="array",
 *                      @OA\Items(type="object",
 *                          @OA\Property(property="name", type="string", example="No Name"),
 *                          @OA\Property(property="avatar", type="string", example="http://advon.me/storage/users/default.png"),
 *                          @OA\Property(property="phone", type="string", example="+380991234567"),
 *                          @OA\Property(property="contacts", type="array",
 *                               @OA\Items(type="object",
 *                                   @OA\Property(property="name", type="string", example="telegram"),
 *                                   @OA\Property(property="value", type="string", example="+380976961719"),
 *                                   @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                                   @OA\Property(property="updated_at", type="string", example="19.04.2021"),
 *                               ),
 *                          ),
 *                          @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                      ),
 *                  ),
 *                  @OA\Property(property="price", type="number", nullable=true, example="12.15"),
 *                  @OA\Property(property="ready_for_political_advertising", type="integer", example="1"),
 *                  @OA\Property(property="hashtags", type="array",
 *                      @OA\Items(type="string", example="hashtag_1"),
 *                      @OA\Items(type="string", example="hashtag_N"),
 *                  ),
 *                 @OA\Property(property="reach_audience", type="integer", example="21"),
 *                 @OA\Property(property="payment", type="integer", example="1"),
 *                 @OA\Property(property="make_and_place_advertising", type="integer", example="2"),
 *                 @OA\Property(property="video", type="string", example="http://advonback/test.mp4"),
 *                 @OA\Property(property="deadline_date", type="string", example="2021-06-04 13:46:33"),
 *                 @OA\Property(property="sample", type="string", example="http://advonback/storage//additional_sample/June2021/Vls58oIv9k5jUo8CyAxHYZAzVyEzMz91uLBPynrk.mp3"),
 *             ),
 *          ),
 *       ),
 * )
 *
 */

/**
 *
 * @OA\Get(
 *      path="/api/v1/getLastAdvertisements",
 *      operationId="getLastAdvertisements",
 *      tags={"Advertisement"},
 *      description="get last Advertisements.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=false,
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
 *      @OA\Response(
 *          response=200,
 *          description="Get Advertisement.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="name", type="string", example="telegram"),
 *                      @OA\Property(property="title", type="string", example="title"),
 *                      @OA\Property(property="price", type="number", nullable=true, example="12.15"),
 *                      @OA\Property(property="price_type", type="string", example="торг возможен"),
 *                      @OA\Property(property="image", type="string", example="http://advonback/storage/advertisements/VG9HWZqGIXYoZ5h8XuFR.jpg"),
 *                      @OA\Property(property="payment_name", type="string", example="в год"),
 *                      @OA\Property(property="category_name", type="string", example="Кузов"),
 *                      @OA\Property(property="translation_currency_code", type="string", example="руб"),
 *                      @OA\Property(property="person", type="string", example="Владислав"),
 *                      @OA\Property(property="city", type="string", example="Ижевск"),
 *                  ),
 *              ),
 *          ),
 *      ),
 *
 * )
 *
 */

/**
 *
 * @OA\Get(
 *      path="/api/v1/getIntersectAdvertisements",
 *      operationId="getIntersectAdvertisements",
 *      tags={"Advertisement"},
 *      description="get intersect Advertisements.",
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
 *      @OA\Response(
 *          response=200,
 *          description="Get Advertisement.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="name", type="string", example="telegram"),
 *                      @OA\Property(property="title", type="string", example="title"),
 *                      @OA\Property(property="price", type="number", nullable=true, example="12.15"),
 *                      @OA\Property(property="price_type", type="string", example="торг возможен"),
 *                      @OA\Property(property="image", type="string", example="http://advonback/storage/advertisements/VG9HWZqGIXYoZ5h8XuFR.jpg"),
 *                      @OA\Property(property="payment_name", type="string", example="в год"),
 *                      @OA\Property(property="category_name", type="string", example="Кузов"),
 *                      @OA\Property(property="translation_currency_code", type="string", example="руб"),
 *                      @OA\Property(property="person", type="string", example="Владислав"),
 *                      @OA\Property(property="city", type="string", example="Ижевск"),
 *                  ),
 *              ),
 *          ),
 *      ),
 *
 * )
 *
 */
