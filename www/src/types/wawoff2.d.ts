declare module 'wawoff2' {
  export function decompress(input: Uint8Array | Buffer): Promise<Uint8Array>;
  export function compress(input: Uint8Array | Buffer): Promise<Uint8Array>;
}
