<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="BizTrack API",
 *   description="BizTrack REST API (Mobile) — v1"
 * )
 *
 * @OA\Server(
 *   url="/api/v1",
 *   description="API v1"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="Sanctum"
 * )
 *
 * @OA\Schema(
 *   schema="PaginationMeta",
 *   type="object",
 *
 *   @OA\Property(property="page", type="integer", example=1),
 *   @OA\Property(property="total", type="integer", example=100)
 * )
 *
 * @OA\Schema(
 *   schema="ApiSuccess",
 *   type="object",
 *   required={"success","data","message"},
 *
 *   @OA\Property(property="success", type="boolean", example=true),
 *   @OA\Property(property="data", nullable=true),
 *   @OA\Property(property="message", type="string", example="OK"),
 *   @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta", nullable=true)
 * )
 *
 * @OA\Schema(
 *   schema="ApiError",
 *   type="object",
 *   required={"success","message"},
 *
 *   @OA\Property(property="success", type="boolean", example=false),
 *   @OA\Property(property="message", type="string", example="Validation error"),
 *   @OA\Property(
 *     property="errors",
 *     type="object",
 *     additionalProperties=@OA\Schema(
 *       type="array",
 *
 *       @OA\Items(type="string", example="The field is required.")
 *     ),
 *     nullable=true
 *   )
 * )
 *
 * @OA\Tag(name="Auth", description="Authentication")
 * @OA\Tag(name="Clients", description="Clients")
 * @OA\Tag(name="Documents", description="Documents")
 * @OA\Tag(name="Quotes", description="Quotes")
 * @OA\Tag(name="Invoices", description="Invoices")
 * @OA\Tag(name="Deals", description="Deals")
 * @OA\Tag(name="Cashflow", description="Cashflow")
 * @OA\Tag(name="Service Templates", description="Service templates / catalog")
 * @OA\Tag(name="Dashboard", description="Dashboard and notifications")
 *
 * // --------------------
 * // Auth
 * // --------------------
 *
 * @OA\Post(
 *   path="/auth/login",
 *   tags={"Auth"},
 *   summary="Login",
 *
 *   @OA\RequestBody(
 *     required=true,
 *
 *     @OA\JsonContent(
 *       required={"email","password"},
 *
 *       @OA\Property(property="email", type="string", example="demo@dealflow.app"),
 *       @OA\Property(property="password", type="string", example="password"),
 *       @OA\Property(property="device_name", type="string", example="mobile")
 *     )
 *   ),
 *
 *   @OA\Response(
 *     response=200,
 *     description="Token issued",
 *
 *     @OA\JsonContent(
 *       allOf={
 *
 *         @OA\Schema(ref="#/components/schemas/ApiSuccess"),
 *         @OA\Schema(
 *
 *           @OA\Property(
 *             property="data",
 *             type="object",
 *             @OA\Property(property="token", type="string", example="1|xxxxxxxxxxxxxxxxxxxx"),
 *             @OA\Property(property="user", type="object"),
 *             @OA\Property(property="tenant", type="object")
 *           )
 *         )
 *       }
 *     )
 *   ),
 *
 *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Post(
 *   path="/auth/logout",
 *   tags={"Auth"},
 *   summary="Logout (revoke current token)",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="Logged out", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Post(
 *   path="/auth/refresh",
 *   tags={"Auth"},
 *   summary="Refresh token (issue new token and revoke current)",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="Refreshed", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Get(
 *   path="/auth/me",
 *   tags={"Auth"},
 *   summary="Get current user + tenant",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="Me", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Put(
 *   path="/auth/profile",
 *   tags={"Auth"},
 *   summary="Update profile",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\RequestBody(
 *     required=true,
 *
 *     @OA\JsonContent(
 *
 *       @OA\Property(property="name", type="string", example="Demo User"),
 *       @OA\Property(property="avatar", type="string", description="Optional avatar path or URL", nullable=true),
 *       @OA\Property(property="phone", type="string", example="+263771234567", nullable=true)
 *     )
 *   ),
 *
 *   @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Put(
 *   path="/auth/password",
 *   tags={"Auth"},
 *   summary="Change password",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\RequestBody(
 *     required=true,
 *
 *     @OA\JsonContent(
 *       required={"current_password","password","password_confirmation"},
 *
 *       @OA\Property(property="current_password", type="string", example="password"),
 *       @OA\Property(property="password", type="string", example="new-password"),
 *       @OA\Property(property="password_confirmation", type="string", example="new-password")
 *     )
 *   ),
 *
 *   @OA\Response(response=200, description="Changed", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ApiError")),
 *   @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * // --------------------
 * // Clients
 * // --------------------
 *
 * @OA\Get(
 *   path="/clients",
 *   tags={"Clients"},
 *   summary="List clients (paginated)",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
 *   @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string", enum={"active","inactive","prospect"})),
 *   @OA\Parameter(name="client_type", in="query", required=false, @OA\Schema(type="string", enum={"individual","company"})),
 *   @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", example=15)),
 *   @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer", example=1)),
 *   @OA\Parameter(name="sort_by", in="query", required=false, @OA\Schema(type="string", example="created_at")),
 *   @OA\Parameter(name="sort_dir", in="query", required=false, @OA\Schema(type="string", enum={"asc","desc"}, example="desc")),
 *
 *   @OA\Response(response=200, description="List", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Post(
 *   path="/clients",
 *   tags={"Clients"},
 *   summary="Create client",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent(
 *     required={"name","client_type","status"},
 *
 *     @OA\Property(property="name", type="string", example="Mhofu Trading (Pvt) Ltd"),
 *     @OA\Property(property="trading_name", type="string", nullable=true),
 *     @OA\Property(property="email", type="string", nullable=true),
 *     @OA\Property(property="phone", type="string", nullable=true),
 *     @OA\Property(property="whatsapp", type="string", nullable=true),
 *     @OA\Property(property="address", type="string", nullable=true),
 *     @OA\Property(property="city", type="string", example="Harare", nullable=true),
 *     @OA\Property(property="country", type="string", example="Zimbabwe"),
 *     @OA\Property(property="client_type", type="string", enum={"individual","company"}),
 *     @OA\Property(property="status", type="string", enum={"active","inactive","prospect"}),
 *     @OA\Property(property="notes", type="string", nullable=true),
 *     @OA\Property(property="assigned_to", type="integer", nullable=true)
 *   )),
 *
 *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Get(
 *   path="/clients/{id}",
 *   tags={"Clients"},
 *   summary="Client detail + stats",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="Detail", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Put(
 *   path="/clients/{id}",
 *   tags={"Clients"},
 *   summary="Update client",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent()),
 *
 *   @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Delete(
 *   path="/clients/{id}",
 *   tags={"Clients"},
 *   summary="Delete client (soft delete)",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="Deleted", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/clients/{id}/documents", tags={"Clients"}, summary="Client documents", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/clients/{id}/quotes", tags={"Clients"}, summary="Client quotes", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/clients/{id}/invoices", tags={"Clients"}, summary="Client invoices", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/clients/{id}/deals", tags={"Clients"}, summary="Client deals", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * // --------------------
 * // Documents
 * // --------------------
 *
 * @OA\Get(
 *   path="/documents",
 *   tags={"Documents"},
 *   summary="List documents (paginated)",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
 *   @OA\Parameter(name="document_type", in="query", required=false, @OA\Schema(type="string")),
 *   @OA\Parameter(name="expiry_status", in="query", required=false, @OA\Schema(type="string", description="expired|expiring|valid")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Post(
 *   path="/documents",
 *   tags={"Documents"},
 *   summary="Upload document (multipart/form-data)",
 *   security={{"bearerAuth":{}}},
 *
 *   @OA\RequestBody(
 *     required=true,
 *
 *     @OA\MediaType(
 *       mediaType="multipart/form-data",
 *
 *       @OA\Schema(
 *         required={"client_id","document_type","title","file"},
 *
 *         @OA\Property(property="client_id", type="integer", example=1),
 *         @OA\Property(property="document_type", type="string", example="tax_clearance"),
 *         @OA\Property(property="title", type="string", example="Tax Clearance 2026"),
 *         @OA\Property(property="issue_date", type="string", format="date", nullable=true),
 *         @OA\Property(property="expiry_date", type="string", format="date", nullable=true),
 *         @OA\Property(property="reminder_days_before", type="integer", example=30, nullable=true),
 *         @OA\Property(property="notes", type="string", nullable=true),
 *         @OA\Property(property="file", type="string", format="binary")
 *       )
 *     )
 *   ),
 *
 *   @OA\Response(response=201, description="Uploaded", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Get(path="/documents/expiring", tags={"Documents"}, summary="Documents expiring within 30 days", security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/documents/{id}", tags={"Documents"}, summary="Document detail + signed URL", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Put(path="/documents/{id}", tags={"Documents"}, summary="Update document metadata", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent()),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Delete(path="/documents/{id}", tags={"Documents"}, summary="Delete document", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/documents/{id}/download", tags={"Documents"}, summary="Download document (signed URL redirect)", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=302, description="Redirect to signed URL")
 * )
 *
 * // --------------------
 * // Quotes
 * // --------------------
 *
 * @OA\Get(path="/quotes", tags={"Quotes"}, summary="List quotes (paginated)", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
 *   @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Post(path="/quotes", tags={"Quotes"}, summary="Create quote with line items", security={{"bearerAuth":{}}},
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent(
 *     required={"client_id","items"},
 *
 *     @OA\Property(property="client_id", type="integer", example=1),
 *     @OA\Property(property="valid_until", type="string", format="date", nullable=true),
 *     @OA\Property(property="notes", type="string", nullable=true),
 *     @OA\Property(property="discount_amount", type="number", format="float", nullable=true),
 *     @OA\Property(property="discount_percent", type="number", format="float", nullable=true),
 *     @OA\Property(
 *       property="items",
 *       type="array",
 *
 *       @OA\Items(type="object",
 *         required={"name","quantity","sell_price"},
 *
 *         @OA\Property(property="service_template_id", type="integer", nullable=true),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="description", type="string", nullable=true),
 *         @OA\Property(property="cost_price", type="number", format="float", nullable=true),
 *         @OA\Property(property="sell_price", type="number", format="float"),
 *         @OA\Property(property="quantity", type="integer", example=1)
 *       )
 *     )
 *   )),
 *
 *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/quotes/{id}", tags={"Quotes"}, summary="Quote detail with items + profit summary", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Put(path="/quotes/{id}", tags={"Quotes"}, summary="Update quote", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent()),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Delete(path="/quotes/{id}", tags={"Quotes"}, summary="Delete quote", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Post(path="/quotes/{id}/convert", tags={"Quotes"}, summary="Convert quote to invoice", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/quotes/{id}/pdf", tags={"Quotes"}, summary="Get quote PDF", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="PDF")
 * )
 *
 * @OA\Post(path="/quotes/{id}/send", tags={"Quotes"}, summary="Send quote via email", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * // --------------------
 * // Invoices
 * // --------------------
 *
 * @OA\Get(path="/invoices", tags={"Invoices"}, summary="List invoices (paginated)", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string")),
 *   @OA\Parameter(name="date_from", in="query", required=false, @OA\Schema(type="string", format="date")),
 *   @OA\Parameter(name="date_to", in="query", required=false, @OA\Schema(type="string", format="date")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Post(path="/invoices", tags={"Invoices"}, summary="Create invoice", security={{"bearerAuth":{}}},
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent()),
 *
 *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/invoices/overdue", tags={"Invoices"}, summary="List overdue invoices", security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/invoices/{id}", tags={"Invoices"}, summary="Invoice detail with payments", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Put(path="/invoices/{id}", tags={"Invoices"}, summary="Update invoice", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent()),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Delete(path="/invoices/{id}", tags={"Invoices"}, summary="Delete invoice", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Post(path="/invoices/{id}/payments", tags={"Invoices"}, summary="Record payment", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent(
 *     required={"amount","payment_method","payment_date"},
 *
 *     @OA\Property(property="amount", type="number", format="float", example=50),
 *     @OA\Property(property="payment_method", type="string", example="ecocash"),
 *     @OA\Property(property="payment_date", type="string", format="date", example="2026-05-12"),
 *     @OA\Property(property="reference", type="string", nullable=true),
 *     @OA\Property(property="notes", type="string", nullable=true)
 *   )),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/invoices/{id}/pdf", tags={"Invoices"}, summary="Get invoice PDF", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="PDF")
 * )
 *
 * @OA\Post(path="/invoices/{id}/send", tags={"Invoices"}, summary="Send invoice via email", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * // --------------------
 * // Deals
 * // --------------------
 *
 * @OA\Get(path="/deals", tags={"Deals"}, summary="List deals (paginated)", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="stage", in="query", required=false, @OA\Schema(type="string")),
 *   @OA\Parameter(name="priority", in="query", required=false, @OA\Schema(type="string")),
 *   @OA\Parameter(name="assigned_to", in="query", required=false, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Post(path="/deals", tags={"Deals"}, summary="Create deal", security={{"bearerAuth":{}}},
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent(
 *     required={"client_id","title","stage","priority"},
 *
 *     @OA\Property(property="client_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Tax clearance renewal"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="stage", type="string", example="lead"),
 *     @OA\Property(property="priority", type="string", example="medium"),
 *     @OA\Property(property="value", type="number", format="float", example=120),
 *     @OA\Property(property="expected_close_date", type="string", format="date", nullable=true),
 *     @OA\Property(property="assigned_to", type="integer", nullable=true)
 *   )),
 *
 *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/deals/{id}", tags={"Deals"}, summary="Deal detail with activity timeline", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Put(path="/deals/{id}", tags={"Deals"}, summary="Update deal", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent()),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Delete(path="/deals/{id}", tags={"Deals"}, summary="Delete deal (soft delete)", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Put(path="/deals/{id}/stage", tags={"Deals"}, summary="Move deal stage", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent(
 *     required={"stage"},
 *
 *     @OA\Property(property="stage", type="string", example="won")
 *   )),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Post(path="/deals/{id}/activities", tags={"Deals"}, summary="Log deal activity", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent(
 *     required={"type","description","date"},
 *
 *     @OA\Property(property="type", type="string", example="note"),
 *     @OA\Property(property="description", type="string", example="Client requested updated proposal."),
 *     @OA\Property(property="date", type="string", format="date", example="2026-05-12")
 *   )),
 *
 *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/deals/pipeline", tags={"Deals"}, summary="Deals grouped by stage (kanban mobile)", security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * // --------------------
 * // Cashflow
 * // --------------------
 *
 * @OA\Get(path="/cashflow", tags={"Cashflow"}, summary="List cashflow entries (paginated)", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="entry_type", in="query", required=false, @OA\Schema(type="string", enum={"income","expense"})),
 *   @OA\Parameter(name="payment_method", in="query", required=false, @OA\Schema(type="string")),
 *   @OA\Parameter(name="date_from", in="query", required=false, @OA\Schema(type="string", format="date")),
 *   @OA\Parameter(name="date_to", in="query", required=false, @OA\Schema(type="string", format="date")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Post(path="/cashflow", tags={"Cashflow"}, summary="Create cashflow entry", security={{"bearerAuth":{}}},
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent(
 *     required={"entry_type","category","amount","payment_method","entry_date"},
 *
 *     @OA\Property(property="entry_type", type="string", enum={"income","expense"}),
 *     @OA\Property(property="category", type="string", example="Sales"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="amount", type="number", format="float", example=120),
 *     @OA\Property(property="payment_method", type="string", example="cash"),
 *     @OA\Property(property="entry_date", type="string", format="date", example="2026-05-12"),
 *     @OA\Property(property="reference", type="string", nullable=true),
 *     @OA\Property(property="client_id", type="integer", nullable=true),
 *     @OA\Property(property="invoice_id", type="integer", nullable=true)
 *   )),
 *
 *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/cashflow/{id}", tags={"Cashflow"}, summary="Cashflow entry detail", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Put(path="/cashflow/{id}", tags={"Cashflow"}, summary="Update cashflow entry", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent()),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Delete(path="/cashflow/{id}", tags={"Cashflow"}, summary="Delete cashflow entry", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/cashflow/summary", tags={"Cashflow"}, summary="Cashflow summary", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="period", in="query", required=false, @OA\Schema(type="string", example="month")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/cashflow/monthly", tags={"Cashflow"}, summary="Monthly breakdown for last 12 months", security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * // --------------------
 * // Service Templates
 * // --------------------
 *
 * @OA\Get(path="/service-templates", tags={"Service Templates"}, summary="List service templates", security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Post(path="/service-templates", tags={"Service Templates"}, summary="Create service template (Pro only)", security={{"bearerAuth":{}}},
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent(
 *     required={"name","sell_price"},
 *
 *     @OA\Property(property="name", type="string", example="Private Company Registration"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="category", type="string", nullable=true),
 *     @OA\Property(property="cost_price", type="number", format="float", nullable=true),
 *     @OA\Property(property="sell_price", type="number", format="float", example=120),
 *     @OA\Property(property="is_active", type="boolean", example=true)
 *   )),
 *
 *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/ApiSuccess")),
 *   @OA\Response(response=403, description="Upgrade required", @OA\JsonContent(ref="#/components/schemas/ApiError"))
 * )
 *
 * @OA\Put(path="/service-templates/{id}", tags={"Service Templates"}, summary="Update service template", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\RequestBody(required=true, @OA\JsonContent()),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Delete(path="/service-templates/{id}", tags={"Service Templates"}, summary="Delete service template", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * // --------------------
 * // Dashboard & Notifications
 * // --------------------
 *
 * @OA\Get(path="/dashboard", tags={"Dashboard"}, summary="Dashboard data", security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Get(path="/notifications", tags={"Dashboard"}, summary="List notifications (paginated)", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer")),
 *   @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Put(path="/notifications/{id}/read", tags={"Dashboard"}, summary="Mark notification read", security={{"bearerAuth":{}}},
 *
 *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 *
 * @OA\Put(path="/notifications/read-all", tags={"Dashboard"}, summary="Mark all notifications read", security={{"bearerAuth":{}}},
 *
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/ApiSuccess"))
 * )
 */
final class ApiV1
{
    // This class exists only as an annotation scan target for swagger-php.
}
