<?php

namespace App\Mcp\Tool;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;

class CurrentTimeTool
{
    #[McpTool(name: 'current-time')]
    public function getCurrentTime(
        #[Schema(description: 'PHP date format string. Default: Y-m-d H:i:s')]
        string $format = 'Y-m-d H:i:s'
    ): string
    {
        return (new \DateTime('now', new \DateTimeZone('UTC')))->format($format);
    }
}