    typedef int SDL_bool;
    typedef uint8_t Uint8;
    typedef uint16_t Uint16;
    typedef uint32_t Uint32;
    typedef void* SDL_RWops;
    typedef void* SDL_version;
    typedef struct SDL_PixelFormat SDL_PixelFormat;
    typedef struct SDL_BlitMap SDL_BlitMap;
    typedef struct SDL_Surface {
        Uint32 flags;
        SDL_PixelFormat *format;
        int w, h;
        int pitch;
        void *pixels;
        void *userdata;
        int locked;
        void *list_blitmap;
        struct SDL_Rect {
            int x, y;
            int w, h;
        } clip_rect;
        SDL_BlitMap *map;
        int refcount;
    } SDL_Surface;
    typedef struct SDL_Color {
        Uint8 r;
        Uint8 g;
        Uint8 b;
        Uint8 a;
    } SDL_Color;
   
extern  const SDL_version *  TTF_Linked_Version(void);
extern  void  TTF_GetFreeTypeVersion(int *major, int *minor, int *patch);
extern  void  TTF_GetHarfBuzzVersion(int *major, int *minor, int *patch);
extern  void  TTF_ByteSwappedUNICODE(SDL_bool swapped);
typedef struct _TTF_Font TTF_Font;
extern  int  TTF_Init(void);
extern  TTF_Font *  TTF_OpenFont(const char *file, int ptsize);
extern  TTF_Font *  TTF_OpenFontIndex(const char *file, int ptsize, long index);
extern  TTF_Font *  TTF_OpenFontRW(SDL_RWops *src, int freesrc, int ptsize);
extern  TTF_Font *  TTF_OpenFontIndexRW(SDL_RWops *src, int freesrc, int ptsize, long index);
extern  TTF_Font *  TTF_OpenFontDPI(const char *file, int ptsize, unsigned int hdpi, unsigned int vdpi);
extern  TTF_Font *  TTF_OpenFontIndexDPI(const char *file, int ptsize, long index, unsigned int hdpi, unsigned int vdpi);
extern  TTF_Font *  TTF_OpenFontDPIRW(SDL_RWops *src, int freesrc, int ptsize, unsigned int hdpi, unsigned int vdpi);
extern  TTF_Font *  TTF_OpenFontIndexDPIRW(SDL_RWops *src, int freesrc, int ptsize, long index, unsigned int hdpi, unsigned int vdpi);
extern  int  TTF_SetFontSize(TTF_Font *font, int ptsize);
extern  int  TTF_SetFontSizeDPI(TTF_Font *font, int ptsize, unsigned int hdpi, unsigned int vdpi);
extern  int  TTF_GetFontStyle(const TTF_Font *font);
extern  void  TTF_SetFontStyle(TTF_Font *font, int style);
extern  int  TTF_GetFontOutline(const TTF_Font *font);
extern  void  TTF_SetFontOutline(TTF_Font *font, int outline);
extern  int  TTF_GetFontHinting(const TTF_Font *font);
extern  void  TTF_SetFontHinting(TTF_Font *font, int hinting);
extern  int  TTF_GetFontWrappedAlign(const TTF_Font *font);
extern  void  TTF_SetFontWrappedAlign(TTF_Font *font, int align);
extern  int  TTF_FontHeight(const TTF_Font *font);
extern  int  TTF_FontAscent(const TTF_Font *font);
extern  int  TTF_FontDescent(const TTF_Font *font);
extern  int  TTF_FontLineSkip(const TTF_Font *font);
extern  int  TTF_GetFontKerning(const TTF_Font *font);
extern  void  TTF_SetFontKerning(TTF_Font *font, int allowed);
extern  long  TTF_FontFaces(const TTF_Font *font);
extern  int  TTF_FontFaceIsFixedWidth(const TTF_Font *font);
extern  const char *  TTF_FontFaceFamilyName(const TTF_Font *font);
extern  const char *  TTF_FontFaceStyleName(const TTF_Font *font);
extern  int  TTF_GlyphIsProvided(TTF_Font *font, Uint16 ch);
extern  int  TTF_GlyphIsProvided32(TTF_Font *font, Uint32 ch);
extern  int  TTF_GlyphMetrics(TTF_Font *font, Uint16 ch,
                        int *minx, int *maxx,
                        int *miny, int *maxy, int *advance);
extern  int  TTF_GlyphMetrics32(TTF_Font *font, Uint32 ch,
                        int *minx, int *maxx,
                        int *miny, int *maxy, int *advance);
extern  int  TTF_SizeText(TTF_Font *font, const char *text, int *w, int *h);
extern  int  TTF_SizeUTF8(TTF_Font *font, const char *text, int *w, int *h);
extern  int  TTF_SizeUNICODE(TTF_Font *font, const Uint16 *text, int *w, int *h);
extern  int  TTF_MeasureText(TTF_Font *font, const char *text, int measure_width, int *extent, int *count);
extern  int  TTF_MeasureUTF8(TTF_Font *font, const char *text, int measure_width, int *extent, int *count);
extern  int  TTF_MeasureUNICODE(TTF_Font *font, const Uint16 *text, int measure_width, int *extent, int *count);
extern  SDL_Surface *  TTF_RenderText_Solid(TTF_Font *font,
                const char *text, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderUTF8_Solid(TTF_Font *font,
                const char *text, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderUNICODE_Solid(TTF_Font *font,
                const Uint16 *text, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderText_Solid_Wrapped(TTF_Font *font,
                const char *text, SDL_Color fg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderUTF8_Solid_Wrapped(TTF_Font *font,
                const char *text, SDL_Color fg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderUNICODE_Solid_Wrapped(TTF_Font *font,
                const Uint16 *text, SDL_Color fg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderGlyph_Solid(TTF_Font *font,
                Uint16 ch, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderGlyph32_Solid(TTF_Font *font,
                Uint32 ch, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderText_Shaded(TTF_Font *font,
                const char *text, SDL_Color fg, SDL_Color bg);
extern  SDL_Surface *  TTF_RenderUTF8_Shaded(TTF_Font *font,
                const char *text, SDL_Color fg, SDL_Color bg);
extern  SDL_Surface *  TTF_RenderUNICODE_Shaded(TTF_Font *font,
                const Uint16 *text, SDL_Color fg, SDL_Color bg);
extern  SDL_Surface *  TTF_RenderText_Shaded_Wrapped(TTF_Font *font,
                const char *text, SDL_Color fg, SDL_Color bg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderUTF8_Shaded_Wrapped(TTF_Font *font,
                const char *text, SDL_Color fg, SDL_Color bg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderUNICODE_Shaded_Wrapped(TTF_Font *font,
                const Uint16 *text, SDL_Color fg, SDL_Color bg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderGlyph_Shaded(TTF_Font *font,
                Uint16 ch, SDL_Color fg, SDL_Color bg);
extern  SDL_Surface *  TTF_RenderGlyph32_Shaded(TTF_Font *font,
                Uint32 ch, SDL_Color fg, SDL_Color bg);
extern  SDL_Surface *  TTF_RenderText_Blended(TTF_Font *font,
                const char *text, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderUTF8_Blended(TTF_Font *font,
                const char *text, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderUNICODE_Blended(TTF_Font *font,
                const Uint16 *text, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderText_Blended_Wrapped(TTF_Font *font,
                const char *text, SDL_Color fg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderUTF8_Blended_Wrapped(TTF_Font *font,
                const char *text, SDL_Color fg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderUNICODE_Blended_Wrapped(TTF_Font *font,
                const Uint16 *text, SDL_Color fg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderGlyph_Blended(TTF_Font *font,
                Uint16 ch, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderGlyph32_Blended(TTF_Font *font,
                Uint32 ch, SDL_Color fg);
extern  SDL_Surface *  TTF_RenderText_LCD(TTF_Font *font,
                const char *text, SDL_Color fg, SDL_Color bg);
extern  SDL_Surface *  TTF_RenderUTF8_LCD(TTF_Font *font,
                const char *text, SDL_Color fg, SDL_Color bg);
extern  SDL_Surface *  TTF_RenderUNICODE_LCD(TTF_Font *font,
                const Uint16 *text, SDL_Color fg, SDL_Color bg);
extern  SDL_Surface *  TTF_RenderText_LCD_Wrapped(TTF_Font *font,
                const char *text, SDL_Color fg, SDL_Color bg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderUTF8_LCD_Wrapped(TTF_Font *font,
                const char *text, SDL_Color fg, SDL_Color bg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderUNICODE_LCD_Wrapped(TTF_Font *font,
                const Uint16 *text, SDL_Color fg, SDL_Color bg, Uint32 wrapLength);
extern  SDL_Surface *  TTF_RenderGlyph_LCD(TTF_Font *font,
                Uint16 ch, SDL_Color fg, SDL_Color bg);
extern  SDL_Surface *  TTF_RenderGlyph32_LCD(TTF_Font *font,
                Uint32 ch, SDL_Color fg, SDL_Color bg);
extern  void  TTF_CloseFont(TTF_Font *font);
extern  void  TTF_Quit(void);
extern  int  TTF_WasInit(void);
extern   int TTF_GetFontKerningSize(TTF_Font *font, int prev_index, int index);
extern  int TTF_GetFontKerningSizeGlyphs(TTF_Font *font, Uint16 previous_ch, Uint16 ch);
extern  int TTF_GetFontKerningSizeGlyphs32(TTF_Font *font, Uint32 previous_ch, Uint32 ch);
extern  int TTF_SetFontSDF(TTF_Font *font, SDL_bool on_off);
extern  SDL_bool TTF_GetFontSDF(const TTF_Font *font);
typedef enum
{
  TTF_DIRECTION_LTR = 0,
  TTF_DIRECTION_RTL,
  TTF_DIRECTION_TTB,
  TTF_DIRECTION_BTT
} TTF_Direction;
extern   int  TTF_SetDirection(int direction);
extern   int  TTF_SetScript(int script);
extern  int  TTF_SetFontDirection(TTF_Font *font, TTF_Direction direction);
extern  int  TTF_SetFontScriptName(TTF_Font *font, const char *script);
