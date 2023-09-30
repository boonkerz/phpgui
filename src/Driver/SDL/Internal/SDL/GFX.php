<?php

namespace PHPGui\Driver\SDL\Internal\SDL;

use FFI\CData;

trait GFX
{
    public function roundedBoxColor(?CData $renderer, int $x1, int $y1, int $x2, int $y2, int $rad, ?CData $color): void
    {
        $this->roundedBoxRGBA($renderer, $x1, $y1, $x2, $y2, $rad, $color->cdata->r, $color->cdata->g, $color->cdata->b, $color->cdata->a);
    }

    public function roundedBoxRGBA(?CData $renderer, int $x1, int $y1, int $x2, int $y2, int $rad,
                                    int $r, int $g, int $b, int $a) : void
    {
        $cx = 0;
        $cy = $rad;
        $ocx = 0xffff;
        $ocy = 0xffff;
        $df = 1 - $rad;
        $d_e = 3;
        $d_se = -2 * $rad + 5;

        if ($rad <= 1) {
            $this->boxRGBA($renderer, $x1, $y1, $x2, $y2, $r, $g, $b, $a);
        }

        if ($x1 == $x2) {
            if ($y1 == $y2) {
                 $this->pixelRGBA($renderer, $x1, $y1, $r, $g, $b, $a);
            } else {
                $this->vlineRGBA($renderer, $x1, $y1, $y2, $r, $g, $b, $a);
            }
        } else {
            if ($y1 == $y2) {
                $this->hlineRGBA($renderer, $x1, $x2, $y1, $r, $g, $b, $a);
            }
        }

        if ($x1 > $x2) {
            $tmp = $x1;
            $x1 = $x2;
            $x2 = $tmp;
        }

        if ($y1 > $y2) {
            $tmp = $y1;
            $y1 = $y2;
            $y2 = $tmp;
        }

        $w = $x2 - $x1 + 1;
        $h = $y2 - $y1 + 1;

        /*
        * Maybe adjust radius
        */
        $r2 = $rad + $rad;
        if ($r2 > $w)
        {
            $rad = (int)($w / 2);
            $r2 = $rad + $rad;
        }
        if ($r2 > $h)
        {
            $rad = $h / 2;
        }

        $x = $x1 + $rad;
        $y = $y1 + $rad;
        $dx = $x2 - $x1 - $rad - $rad;
        $dy = $y2 - $y1 - $rad - $rad;

        $this->SDL_SetRenderDrawBlendMode($renderer, ($a == 255)? Type::SDL_BLENDMODE_NONE->value: Type::SDL_BLENDMODE_BLEND->value);
        $this->SDL_SetRenderDrawColor($renderer, $r, $g, $b, $a);

        do {
            $xpcx = $x + $cx;
            $xmcx = $x - $cx;
            $xpcy = $x + $cy;
            $xmcy = $x - $cy;
            if ($ocy != $cy) {
                if ($cy > 0) {
                    $ypcy = $y + $cy;
                    $ymcy = $y - $cy;
                    $this->_hline($renderer, $xmcx, $xpcx + $dx, $ypcy + $dy);
                    $this->_hline($renderer, $xmcx, $xpcx + $dx, $ymcy);
                } else {
                    $this->_hline($renderer, $xmcx, $xpcx + $dx, $y);
                }
                $ocy = $cy;
            }
            if ($ocx != $cx) {
                if ($cx != $cy) {
                    if ($cx > 0) {
                        $ypcx = $y + $cx;
                        $ymcx = $y - $cx;
                        $this->_hline($renderer, $xmcy, $xpcy + $dx, $ymcx);
                        $this->_hline($renderer, $xmcy, $xpcy + $dx, $ypcx + $dy);
                    } else {
                        $this->_hline($renderer, $xmcy, $xpcy + $dx, $y);
                    }
                }
                $ocx = $cx;
            }

            /*
            * Update
            */
            if ($df < 0) {
                $df += $d_e;
                $d_e += 2;
                $d_se += 2;
            } else {
                $df += $d_se;
                $d_e += 2;
                $d_se += 4;
                $cy--;
            }
            $cx++;
        } while ($cx <= $cy);

        /* Inside */
        if ($dx > 0 && $dy > 0) {
            $this->boxRGBA($renderer, $x1, $y1 + $rad + 1, $x2, $y2 - $rad, $r, $g, $b, $a);
        }

    }

    public function rectangleRGBA(?CData $renderer, int $x1, int $y1, int $x2, int $y2, int $r, int $g, int $b, int $a)
    {

        if ($x1 == $x2) {
            if ($y1 == $y2) {
                return ($this->pixelRGBA($renderer, $x1, $y1, $r, $g, $b, $a));
            } else {
                return ($this->vlineRGBA($renderer, $x1, $y1, $y2, $r, $g, $b, $a));
            }
        } else {
            if ($y1 == $y2) {
                return ($this->hlineRGBA($renderer, $x1, $x2, $y1, $r, $g, $b, $a));
            }
        }

        if ($x1 > $x2) {
            $tmp = $x1;
            $x1 = $x2;
            $x2 = $tmp;
        }

        if ($y1 > $y2) {
            $tmp = $y1;
            $y1 = $y2;
            $y2 = $tmp;
        }

        $rect = $this->new('SDL_Rect');
        $rect->x = $x1;
        $rect->y = $y1;
        $rect->w = $x2 - $x1;
        $rect->h = $y2 - $y1;

        $this->SDL_SetRenderDrawBlendMode($renderer, ($a == 255)? Type::SDL_BLENDMODE_NONE->value: Type::SDL_BLENDMODE_BLEND->value);
        $this->SDL_SetRenderDrawColor($renderer, $r, $g, $b, $a);
        $this->SDL_RenderDrawRect($renderer, \FFI::addr($rect));
    }

    public function boxRGBA(?CData $renderer, int $x1, int $y1, int $x2, int $y2, int $r, int $g, int $b, int $a): void
    {
        if ($x1 == $x2) {
            if ($y1 == $y2) {
                $this->pixelRGBA($renderer, $x1, $y1, $r, $g, $b, $a);
            } else {
                $this->vlineRGBA($renderer, $x1, $y1, $y2, $r, $g, $b, $a);
            }
        } else {
            if ($y1 == $y2) {
                $this->hlineRGBA($renderer, $x1, $x2, $y1, $r, $g, $b, $a);
            }
        }

        if ($x1 > $x2) {
            $tmp = $x1;
            $x1 = $x2;
            $x2 = $tmp;
        }

        if ($y1 > $y2) {
            $tmp = $y1;
            $y1 = $y2;
            $y2 = $tmp;
        }

        $rect = $this->new('SDL_Rect');
        $rect->x = $x1;
        $rect->y = $y1;
        $rect->w = $x2 - $x1 + 1;
        $rect->h = $y2 - $y1 + 1;

        $this->SDL_SetRenderDrawBlendMode($renderer, ($a == 255)? Type::SDL_BLENDMODE_NONE->value: Type::SDL_BLENDMODE_BLEND->value);
        $this->SDL_SetRenderDrawColor($renderer, $r, $g, $b, $a);
        $this->SDL_RenderFillRect($renderer, \FFI::addr($rect));

    }

    public function roundedRectangleRGBA(?CData $renderer, int $x1, int $y1, int $x2, int $y2, int $rad, int $r, int $g, int $b, int $a):void
    {

        /*
        * Special case - no rounding
        */
        if ($rad <= 1) {
            $this->rectangleRGBA($renderer, $x1, $y1, $x2, $y2, $r, $g, $b, $a);
        }


        if ($x1 == $x2) {
            if ($y1 == $y2) {
                $this->pixelRGBA($renderer, $x1, $y1, $r, $g, $b, $a);
            } else {
                $this->vlineRGBA($renderer, $x1, $y1, $y2, $r, $g, $b, $a);
            }
        } else {
            if ($y1 == $y2) {
                $this->hlineRGBA($renderer, $x1, $x2, $y1, $r, $g, $b, $a);
            }
        }

        if ($x1 > $x2) {
            $tmp = $x1;
            $x1 = $x2;
            $x2 = $tmp;
        }

        if ($y1 > $y2) {
            $tmp = $y1;
            $y1 = $y2;
            $y2 = $tmp;
        }

        $w = $x2 - $x1;
        $h = $y2 - $y1;

        if (($rad * 2) > $w)
        {
            $rad = $w / 2;
        }
        if (($rad * 2) > $h)
        {
            $rad = $h / 2;
        }

        $xx1 = $x1 + $rad;
        $xx2 = $x2 - $rad;
        $yy1 = $y1 + $rad;
        $yy2 = $y2 - $rad;
        $this->arcRGBA($renderer, $xx1, $yy1, $rad, 180, 270, $r, $g, $b, $a);
        $this->arcRGBA($renderer, $xx2, $yy1, $rad, 270, 360, $r, $g, $b, $a);
        $this->arcRGBA($renderer, $xx1, $yy2, $rad,  90, 180, $r, $g, $b, $a);
        $this->arcRGBA($renderer, $xx2, $yy2, $rad,   0,  90, $r, $g, $b, $a);

        if ($xx1 <= $xx2) {
            $this->hlineRGBA($renderer, $xx1, $xx2, $y1, $r, $g, $b, $a);
            $this->hlineRGBA($renderer, $xx1, $xx2, $y2, $r, $g, $b, $a);
        }
        if ($yy1 <= $yy2) {
            $this->vlineRGBA($renderer, $x1, $yy1, $yy2, $r, $g, $b, $a);
            $this->vlineRGBA($renderer, $x2, $yy1, $yy2, $r, $g, $b, $a);
        }
    }
    public function _hline(?CData $renderer, int $x1, int $x2, int $y): void
    {
        $this->SDL_RenderDrawLine($renderer, $x1, $y, $x2, $y);
    }

    public function arcRGBA(?CData $renderer, int $x, int $y, int $rad, int $start, int $end, int $r, int $g, int $b, int $a): void
    {
        $cx = 0;
        $cy = $rad;
        $df = 1 - $rad;
        $d_e = 3;
        $d_se = -2 * $rad + 5;
        $stopval_start = 0; $stopval_end = 0;
        $temp = 0.;


        /*
        * Special case for rad=0 - draw a point
        */
        if ($rad == 0) {
            $this->pixelRGBA($renderer, $x, $y, $r, $g, $b, $a);
        }

        /*
         Octant labeling

          \ 5 | 6 /
           \  |  /
          4 \ | / 7
             \|/
        ------+------ +x
             /|\
          3 / | \ 0
           /  |  \
          / 2 | 1 \
              +y

         Initially reset bitmask to 0x00000000
         the set whether or not to keep drawing a given octant.
         For example: 0x00111100 means we're drawing in octants 2-5
        */
        $drawoct = 0;

        /*
        * Fixup angles
        */
        $start %= 360;
        $end %= 360;
        /* 0 <= start & end < 360; note that sometimes start > end - if so, arc goes back through 0. */
        while ($start < 0) $start += 360;
        while ($end < 0) $end += 360;
        $start %= 360;
        $end %= 360;

        /* now, we find which octants we're drawing in. */
        $startoct = $start / 45;
        $endoct = $end / 45;
        $oct = $startoct - 1;

        /* stopval_start, stopval_end; what values of cx to stop at. */
        do {
            $oct = ($oct + 1) % 8;

            if ($oct == $startoct) {
                /* need to compute stopval_start for this octant.  Look at picture above if this is unclear */
                $dstart = (double)$start;
                switch ($oct)
                {
                    case 0:
                    case 3:
                        $temp = sin($dstart * M_PI / 180.);
                        break;
                    case 1:
                    case 6:
                        $temp = cos($dstart * M_PI / 180.);
                        break;
                    case 2:
                    case 5:
                        $temp = -cos($dstart * M_PI / 180.);
                        break;
                    case 4:
                    case 7:
                        $temp = -sin($dstart * M_PI / 180.);
                        break;
                }
                $temp *= $rad;
                $stopval_start = (int)$temp;

                /*
                This isn't arbitrary, but requires graph paper to explain well.
                The basic idea is that we're always changing drawoct after we draw, so we
                stop immediately after we render the last sensible pixel at x = ((int)temp).
                and whether to draw in this octant initially
                */
                if ($oct % 2) $drawoct |= (1 << $oct);			/* this is basically like saying drawoct[oct] = true, if drawoct were a bool array */
                else		 $drawoct &= 255 - (1 << $oct);	/* this is basically like saying drawoct[oct] = false */
            }
            if ($oct == $endoct) {
                /* need to compute stopval_end for this octant */
                $dend = (double)$end;
                switch ($oct)
                {
                    case 0:
                    case 3:
                        $temp = sin($dend * M_PI / 180);
                        break;
                    case 1:
                    case 6:
                        $temp = cos($dend * M_PI / 180);
                        break;
                    case 2:
                    case 5:
                        $temp = -cos($dend * M_PI / 180);
                        break;
                    case 4:
                    case 7:
                        $temp = -sin($dend * M_PI / 180);
                        break;
                }
                $temp *= $rad;
                $stopval_end = (int)$temp;

                /* and whether to draw in this octant initially */
                if ($startoct == $endoct)	{
                    /* note:      we start drawing, stop, then start again in this case */
                    /* otherwise: we only draw in this octant, so initialize it to false, it will get set back to true */
                    if ($start > $end) {
                        /* unfortunately, if we're in the same octant and need to draw over the whole circle, */
                        /* we need to set the rest to true, because the while loop will end at the bottom. */
                        $drawoct = 255;
                    } else {
                        $drawoct &= 255 - (1 << $oct);
                    }
                }
                else if ($oct % 2) $drawoct &= 255 - (1 << $oct);
                else			  $drawoct |= (1 << $oct);
            } else if ($oct != $startoct) { /* already verified that it's != endoct */
                $drawoct |= (1 << $oct); /* draw this entire segment */
            }
        } while ($oct != $endoct);

        /* so now we have what octants to draw and when to draw them. all that's left is the actual raster code. */

        /*
        * Set color
        */
        $this->SDL_SetRenderDrawBlendMode($renderer, ($a == 255) ? Type::SDL_BLENDMODE_NONE->value: Type::SDL_BLENDMODE_BLEND->value);
        $this->SDL_SetRenderDrawColor($renderer, $r, $g, $b, $a);

        /*
        * Draw arc
        */
        do {
            $ypcy = $y + $cy;
            $ymcy = $y - $cy;
            if ($cx > 0) {
                $xpcx = $x + $cx;
                $xmcx = $x - $cx;

                /* always check if we're drawing a certain octant before adding a pixel to that octant. */
                if ($drawoct & 4)  $this->pixel($renderer, $xmcx, $ypcy);
                if ($drawoct & 2)  $this->pixel($renderer, $xpcx, $ypcy);
                if ($drawoct & 32) $this->pixel($renderer, $xmcx, $ymcy);
                if ($drawoct & 64) $this->pixel($renderer, $xpcx, $ymcy);
            } else {
                if ($drawoct & 96) $this->pixel($renderer, $x, $ymcy);
                if ($drawoct & 6)  $this->pixel($renderer, $x, $ypcy);
            }

            $xpcy = $x + $cy;
            $xmcy = $x - $cy;
            if ($cx > 0 && $cx != $cy) {
                $ypcx = $y + $cx;
                $ymcx = $y - $cx;
                if ($drawoct & 8)   $this->pixel($renderer, $xmcy, $ypcx);
                if ($drawoct & 1)   $this->pixel($renderer, $xpcy, $ypcx);
                if ($drawoct & 16)  $this->pixel($renderer, $xmcy, $ymcx);
                if ($drawoct & 128) $this->pixel($renderer, $xpcy, $ymcx);
            } else if ($cx == 0) {
                if ($drawoct & 24)  $this->pixel($renderer, $xmcy, $y);
                if ($drawoct & 129) $this->pixel($renderer, $xpcy, $y);
            }

            /*
            * Update whether we're drawing an octant
            */
            if ($stopval_start == $cx) {
                /* works like an on-off switch. */
                /* This is just in case start & end are in the same octant. */
                if ($drawoct & (1 << $startoct)) $drawoct &= 255 - (1 << $startoct);
                else						   $drawoct |= (1 << $startoct);
            }
            if ($stopval_end == $cx) {
                if ($drawoct & (1 << $endoct)) $drawoct &= 255 - (1 << $endoct);
                else						 $drawoct |= (1 << $endoct);
            }

            /*
            * Update pixels
            */
            if ($df < 0) {
                $df += $d_e;
                $d_e += 2;
                $d_se += 2;
            } else {
                $df += $d_se;
                $d_e += 2;
                $d_se += 4;
                $cy--;
            }
            $cx++;
        } while ($cx <= $cy);
    }

    public function pixel(?CData $renderer, int $x, int $y): void
    {
        $this->SDL_RenderDrawPoint($renderer, $x, $y);
    }

    public function hlineRGBA(?CData $renderer, int $x1, int $x2, int $y, int $r, int $g, int $b, int $a): void
    {
        $this->SDL_SetRenderDrawBlendMode($renderer, ($a == 255) ? Type::SDL_BLENDMODE_NONE->value: Type::SDL_BLENDMODE_BLEND->value);
        $this->SDL_SetRenderDrawColor($renderer, $r, $g, $b, $a);
        $this->SDL_RenderDrawLine($renderer, $x1, $y, $x2, $y);
    }

    public function vlineRGBA(?CData $renderer, int $x, int $y1, int $y2, int $r, int $g, int $b, int $a): void
    {
        $this->SDL_SetRenderDrawBlendMode($renderer, ($a == 255) ? Type::SDL_BLENDMODE_NONE->value: Type::SDL_BLENDMODE_BLEND->value);
        $this->SDL_SetRenderDrawColor($renderer, $r, $g, $b, $a);
        $this->SDL_RenderDrawLine($renderer, $x, $y1, $x, $y2);
    }

    public function pixelRGBA(?CData $renderer, int $x, int $y, int $r, int $g, int $b, int $a): void
    {
        $this->SDL_SetRenderDrawBlendMode($renderer, ($a == 255) ? Type::SDL_BLENDMODE_NONE->value: Type::SDL_BLENDMODE_BLEND->value);
        $this->SDL_SetRenderDrawColor($renderer, $r, $g, $b, $a);
        $this->SDL_RenderDrawPoint($renderer, $x, $y);
}
}
