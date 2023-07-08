import { Matrix4 as Ne, Vector3 as we, Quaternion as Ut, Scene as It, WebGLRenderer as pi, sRGBEncoding as mi, PerspectiveCamera as ci, Group as Dt } from "three";
import { o as S, b as N, c as g, d as ue, E as O, A as Gt, e as ce, m as W, f as G, s as q, g as Y, h as ie, t as Te, B as Kt, D as Jt, i as Qt, L as Xt, r as v, j as le, S as Zt, M as Yt, T as pe, k as Z, l as $t, n as Mt, p as te, R as es, q as ts, u as ss, v as ke, w as X, x as me, y as Ct, z as de, F as di, G as hi, H as Xe, I as Ze, J as se, K as Le, N as Ye, O as Me, P as as, Q as rs, U as et, V as tt, W as fi, X as R, Y as oe, Z as st, _ as at, $ as yi, a0 as ns, a1 as is, a2 as os, a3 as gi, a4 as U, a5 as bi, a6 as rt, a7 as Ni, a8 as wi, a9 as Ti, aa as Si, ab as vi, ac as Oi, ad as Ve, ae as Fe, af as _i, ag as Ai, ah as Ei, ai as ki, aj as Ii, ak as Di, al as $i, am as Ci, an as us, ao as ls, ap as zt, aq as x, ar as zi, as as ps, at as xi, au as Li, av as Vi, aw as Fi, ax as Pi, ay as ms, az as Ri, aA as cs, aB as ji, aC as Bi, aD as Hi, aE as Wi, aF as qi, aG as Ui, aH as Gi, aI as Ki, aJ as Ji, aK as Se, aL as M, aM as nt, aN as Qi, aO as Xi, aP as Zi, aQ as Yi, aR as Mi, aS as eo, aT as to, aU as so, aV as ao, aW as ro, aX as no, aY as io, aZ as oo, a_ as uo, a$ as lo, b0 as po, b1 as mo, b2 as co, b3 as ho, b4 as fo, b5 as yo, b6 as go, b7 as it, b8 as ds, b9 as bo, ba as No, bb as wo, bc as To, bd as So, be as vo, bf as Oo, bg as _o, bh as Ao, bi as Eo, bj as ko, bk as Io, bl as Do, bm as $o, bn as Co, bo as zo, bp as xo, bq as Lo, br as Vo, bs as Fo, bt as Po, bu as Ro, bv as jo, bw as Bo, bx as Ho, by as Wo, bz as qo, bA as Uo, bB as Go, bC as Ko, bD as Jo, bE as Qo, bF as Xo, bG as Zo, bH as Yo, bI as Mo, bJ as eu, bK as tu, bL as su, bM as au, bN as ru, bO as nu, bP as iu, bQ as ou, bR as uu, bS as lu, bT as pu, bU as mu, bV as cu, bW as du, bX as hu, bY as fu, bZ as yu, b_ as gu, b$ as bu, c0 as ot, c1 as Nu, c2 as wu, c3 as Tu, c4 as Su, c5 as vu, c6 as Ou, c7 as _u, c8 as Au, c9 as Eu, ca as ku, cb as Iu, cc as Du, cd as $u, ce as Cu, cf as zu, cg as xu, ch as Lu, ci as Vu, cj as Fu, ck as Pu, cl as Ru, cm as ju, cn as ut, co as lt, cp as Bu, cq as Hu, cr as Wu, cs as qu, ct as Uu, cu as Gu, cv as Ku, cw as Ju, cx as pt, cy as F, cz as hs, cA as fs, cB as ys, cC as gs, cD as bs, cE as Ns, cF as ws, cG as Ts, cH as Ss, cI as vs, cJ as Os, cK as _s, cL as As, cM as Es, cN as ks, cO as Is, cP as Ds, cQ as $s, cR as Cs, cS as zs, cT as xs, cU as Ls, cV as Vs, cW as Fs, cX as Ps, cY as Rs, cZ as js, c_ as Bs, c$ as Hs, d0 as Ws, d1 as qs, d2 as Us, d3 as Gs, d4 as Ks, d5 as Js, d6 as Qs, d7 as Xs, d8 as Zs, d9 as Ys, da as Ms, db as ea, dc as ta, dd as sa, de as aa, df as ra, dg as na, dh as ia, di as oa, dj as ua, dk as la, dl as pa, dm as mt, dn as ma, dp as ca, dq as da, dr as ha, ds as fa, dt as ya, du as ga, dv as ba, dw as Na, dx as wa, dy as ct, dz as Ta, dA as Sa, dB as va, dC as Oa, dD as _a, dE as Aa, dF as Ea, dG as ka, dH as Ia, dI as Da, dJ as $a, dK as Ca, dL as za, dM as xa, dN as La, dO as Va, dP as Fa, dQ as Pa, dR as Ra, dS as ja, dT as Ba, dU as Ha, dV as Wa, dW as qa, dX as Ua, dY as Ga, dZ as Ka, d_ as Ja, d$ as Qa, e0 as Xa, e1 as Za, e2 as Ya, e3 as Ma, e4 as er, e5 as tr, e6 as sr, e7 as ar, e8 as rr, e9 as nr, ea as ir, eb as or, ec as ur, ed as lr, ee as pr, ef as mr, eg as cr, eh as dr, ei as hr, ej as fr, ek as yr, el as gr, em as br, en as Nr, eo as wr, ep as Tr, eq as Sr, er as vr, es as Or, et as _r, eu as Ar, ev as Er, ew as kr, ex as Ir, ey as Dr, ez as $r, eA as ee, eB as Cr, eC as zr, eD as xr, eE as Lr, eF as Vr, eG as dt, eH as ve, eI as Fr, eJ as Pr, eK as Rr, eL as jr, eM as Br, eN as Hr, eO as ae, eP as Wr, eQ as qr, eR as Ur, eS as Gr, eT as P, eU as z, eV as Oe, eW as Kr, eX as ht, eY as Ie, eZ as Qu, e_ as Jr, e$ as Xu, f0 as Qr, f1 as Xr, f2 as Zu, f3 as Yu, f4 as Zr, f5 as Mu, f6 as el, f7 as tl, f8 as sl, f9 as al, fa as rl, fb as nl, fc as il, fd as ol, fe as ul, ff as ll, fg as pl, fh as ml, fi as cl, fj as dl, fk as hl, fl, fm as yl, fn as gl, fo as bl, fp as Nl, fq as wl, fr as Tl, fs as Sl, ft as vl, fu as Ol, fv as _l, fw as Al, fx as El, fy as kl, fz as Il, fA as Dl, fB as $l, fC as Cl, fD as zl, fE as xl, fF as Ll, fG as Vl, fH as Fl, fI as Pl, fJ as Rl, fK as jl, fL as Bl, fM as Hl, fN as Wl, fO as ql, fP as Ul, fQ as Gl, fR as Kl, fS as Jl, fT as Ql, fU as Xl, fV as Zl, fW as Yl, fX as Ml, fY as ep, fZ as tp, f_ as sp, f$ as ap, g0 as rp, g1 as np, g2 as ip, g3 as op, g4 as up, g5 as lp, g6 as pp, g7 as mp, g8 as cp, g9 as dp, ga as hp, gb as fp, gc as yp, gd as gp, ge as bp, gf as Np, gg as wp, gh as Tp, gi as Sp, gj as vp, gk as Op, gl as _p, gm as Ap, gn as Ep, go as kp, gp as Ip, gq as Dp, gr as $p, gs as Cp, gt as zp, gu as xp, gv as Lp, gw as Vp, gx as Fp, gy as Pp, gz as Rp, gA as jp, gB as Bp, gC as Hp, gD as Wp, gE as qp, gF as Up, gG as Gp, gH as Kp, gI as Jp, gJ as Qp, gK as Xp, gL as Zp, gM as Yp, gN as Mp, gO as em, gP as tm, gQ as sm, gR as am, gS as rm, gT as nm, gU as im, gV as om, gW as um, gX as lm, gY as pm, gZ as mm, g_ as cm, g$ as dm, h0 as hm, h1 as fm, h2 as ym, h3 as gm, h4 as bm, h5 as Nm, h6 as wm, h7 as Tm, h8 as Sm, h9 as vm, ha as Om, hb as _m, hc as Am, hd as Em, he as km, hf as Im, hg as Dm, hh as $m, hi as Cm, hj as zm, hk as xm, hl as Lm, hm as Vm, hn as Fm, ho as Pm, hp as Rm, hq as jm, hr as Bm, hs as Hm, ht as Wm, hu as qm, hv as Um, hw as Gm, hx as Km, hy as Jm, hz as Qm, hA as Xm, hB as Zm, hC as Ym, hD as Mm, hE as ec, hF as tc, hG as sc, hH as ac, hI as rc, hJ as nc, hK as ic, hL as oc, hM as uc, hN as lc, hO as pc, hP as mc, hQ as cc, hR as dc, hS as hc, hT as fc, hU as yc, hV as gc, hW as bc, hX as Nc, hY as wc, hZ as Tc, h_ as Sc, h$ as vc, i0 as Oc, i1 as _c, i2 as Ac, i3 as Ec, i4 as kc, i5 as Ic, i6 as Dc, i7 as $c, i8 as Cc, i9 as zc, ia as xc, ib as Lc, ic as Vc, id as Fc, ie as Pc, ig as Rc, ih as jc, ii as Bc, ij as Hc, ik as Wc, il as qc, im as Uc, io as Gc, ip as Kc, iq as Jc, ir as Qc, is as Xc, it as Zc, iu as Yc, iv as Mc, iw as ed, ix as td, iy as sd, iz as ad, iA as rd, iB as nd, iC as id, iD as od, iE as ud, iF as ld, iG as pd, C as md } from "./controller-495b585f.js";
import { CSS3DRenderer as cd } from "three/addons/renderers/CSS3DRenderer.js";
import { U as dd } from "./ui-85e81035.js";
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function hd(s) {
  N(Array.isArray(s), () => "The argument passed to tf.addN() must be a list of tensors"), N(s.length >= 1, () => `Must pass at least one tensor to tf.addN(), but got ${s.length}`);
  const e = s.map((r, n) => g(r, `tensors${n}`, "addN")), t = e[0];
  e.forEach((r) => {
    if (r.dtype !== t.dtype)
      throw new Error("All tensors passed to tf.addN() must have the same dtype");
  }), e.forEach((r) => {
    if (!ue(r.shape, t.shape))
      throw new Error("All tensors passed to tf.addN() must have the same shape");
  });
  const a = e;
  return O.runKernel(Gt, a);
}
const Yr = /* @__PURE__ */ S({ addN_: hd });
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function fd(s, e, t, a, r, n) {
  const o = g(s, "forgetBias", "basicLSTMCell"), u = g(e, "lstmKernel", "basicLSTMCell"), l = g(t, "lstmBias", "basicLSTMCell"), p = g(a, "data", "basicLSTMCell"), m = g(r, "c", "basicLSTMCell"), c = g(n, "h", "basicLSTMCell"), d = ce([p, c], 1), h = W(d, u), b = G(h, l), f = b.shape[0], y = b.shape[1] / 4, T = [f, y], _ = q(b, [0, 0], T), w = q(b, [0, y], T), I = q(b, [0, y * 2], T), E = q(b, [0, y * 3], T), D = G(Y(ie(_), Te(w)), Y(m, ie(G(o, I)))), V = Y(Te(D), ie(E));
  return [D, V];
}
const Mr = /* @__PURE__ */ S({ basicLSTMCell_: fd });
/**
 * @license
 * Copyright 2021 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function yd(s, e) {
  const t = g(s, "s0", "broadcastArgs", "int32"), a = g(e, "s1", "broadcastArgs", "int32");
  if (t.rank !== 1)
    throw new Error(`broadcastArgs(): first input must be a vector (rank=1). Has rank ${t.rank}`);
  if (a.rank !== 1)
    throw new Error(`broadcastArgs(): second input must be a vector (rank=1). Has rank ${a.rank}`);
  const r = { s0: t, s1: a };
  return O.runKernel(Kt, r);
}
const en = /* @__PURE__ */ S({ broadcastArgs_: yd });
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function gd(s) {
  const t = { x: g(s, "x", "diag") };
  return O.runKernel(Jt, t);
}
const tn = /* @__PURE__ */ S({ diag_: gd });
/**
 * @license
 * Copyright 2021 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function bd(s, ...e) {
  const t = e.map((r, n) => g(r, `tensors${n}`, "einsum")), a = { equation: s };
  return O.runKernel(Qt, t, a);
}
const sn = /* @__PURE__ */ S({ einsum_: bd });
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function an(s, e, t) {
  if (t <= 0)
    throw new Error("The number of values should be positive.");
  const a = { start: s, stop: e, num: t };
  return O.runKernel(Xt, {}, a);
}
/**
 * @license
 * Copyright 2022 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const he = 2147483648;
function Nd(s, e, t = "left") {
  const a = g(s, "sortedSequence", "searchSorted"), r = g(e, "values", "searchSorted"), n = a.shape[a.shape.length - 1], o = r.shape[r.shape.length - 1], u = v(a, [-1, n]), l = v(r, [-1, o]);
  if (u.rank < 2)
    throw new Error("Sorted input argument must be at least 2-dimensional");
  if (u.shape[0] !== l.shape[0])
    throw new Error("Leading dimension of 'sortedSequence' and 'values' must match.");
  if (le(l.shape) >= he)
    throw new Error(`values tensor size must less than ${he}`);
  if (u.shape[1] >= he)
    throw new Error(`trailing dim_size must less than ${he} for int32 output type, was ${u.shape[1]}`);
  const p = {
    sortedSequence: u,
    values: l
  }, m = { side: t };
  return O.runKernel(Zt, p, m);
}
const De = /* @__PURE__ */ S({ searchSorted_: Nd });
/**
 * @license
 * Copyright 2022 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function rn(s, e) {
  return De(s, e, "left");
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function wd(s, e, t, a, r = !1) {
  const o = { x: g(s, "x", "maxPoolWithArgmax") }, u = { filterSize: e, strides: t, pad: a, includeBatchInIndex: r }, l = O.runKernel(Yt, o, u);
  return { result: l[0], indexes: l[1] };
}
const nn = /* @__PURE__ */ S({ maxPoolWithArgmax_: wd });
/**
 * @license
 * Copyright 2021 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function on(s, e, { indexing: t = "xy" } = {}) {
  if (t !== "xy" && t !== "ij")
    throw new TypeError(`${t} is not a valid third argument to meshgrid`);
  if (s === void 0)
    return [];
  let a = g(s, "x", "meshgrid", s instanceof pe ? s.dtype : "float32");
  if (e === void 0)
    return [a];
  let r = g(e, "y", "meshgrid", e instanceof pe ? e.dtype : "float32");
  const n = le(a.shape), o = le(r.shape);
  return t === "xy" ? (a = v(a, [1, -1]), r = v(r, [-1, 1]), [
    W(Z([o, 1], a.dtype), a),
    W(r, Z([1, n], r.dtype))
  ]) : (a = v(a, [-1, 1]), r = v(r, [1, -1]), [
    W(a, Z([1, o], a.dtype)),
    W(Z([n, 1], r.dtype), r)
  ]);
}
function Td(s, e, t, a) {
  const r = g(e, "data", "multiRNNCell"), n = $t(t, "c", "multiRNNCell"), o = $t(a, "h", "multiRNNCell");
  let u = r;
  const l = [];
  for (let c = 0; c < s.length; c++) {
    const d = s[c](u, n[c], o[c]);
    l.push(d[0]), l.push(d[1]), u = d[1];
  }
  const p = [], m = [];
  for (let c = 0; c < l.length; c += 2)
    p.push(l[c]), m.push(l[c + 1]);
  return [p, m];
}
const un = /* @__PURE__ */ S({ multiRNNCell_: Td });
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Sd(s, e, t, a = !1) {
  const r = g(s, "logits", "multinomial"), n = r.size, o = r.rank;
  if (n < 2)
    throw new Error(`Error in multinomial: you need at least 2 outcomes, but got ${n}.`);
  if (o > 2)
    throw new Error(`Rank of probabilities must be 1 or 2, but is ${o}`);
  t = t || Math.random();
  const l = { logits: o === 1 ? v(r, [1, -1]) : r }, p = { numSamples: e, seed: t, normalized: a }, m = O.runKernel(Mt, l, p);
  return o === 1 ? v(m, [m.size]) : m;
}
const ln = /* @__PURE__ */ S({ multinomial_: Sd });
function vd(s, e) {
  const t = g(s, "v1", "outerProduct"), a = g(e, "v2", "outerProduct");
  N(t.rank === 1 && a.rank === 1, () => `Error in outerProduct: inputs must be rank 1, but got ranks ${t.rank} and ${a.rank}.`);
  const r = v(t, [-1, 1]), n = v(a, [1, -1]);
  return W(r, n);
}
const pn = /* @__PURE__ */ S({ outerProduct_: vd });
function Od(s, e, t = 0) {
  return N(e.length === 2, () => "Invalid number of paddings. Must be length of 2."), te(s, [e], t);
}
const mn = /* @__PURE__ */ S({ pad1d_: Od });
function _d(s, e, t = 0) {
  return N(e.length === 2 && e[0].length === 2 && e[1].length === 2, () => "Invalid number of paddings. Must be length of 2 each."), te(s, e, t);
}
const cn = /* @__PURE__ */ S({ pad2d_: _d });
function Ad(s, e, t = 0) {
  return N(e.length === 3 && e[0].length === 2 && e[1].length === 2 && e[2].length === 2, () => "Invalid number of paddings. Must be length of 2 each."), te(s, e, t);
}
const dn = /* @__PURE__ */ S({ pad3d_: Ad });
function Ed(s, e, t = 0) {
  return N(e.length === 4 && e[0].length === 2 && e[1].length === 2 && e[2].length === 2 && e[3].length === 2, () => "Invalid number of paddings. Must be length of 2 each."), te(s, e, t);
}
const hn = /* @__PURE__ */ S({ pad4d_: Ed });
/**
 * @license
 * Copyright 2022 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function kd(s, e, t, a) {
  const r = s.map((m, c) => g(m, `tensors${c}`, "raggedGather", "int32")), n = g(e, "paramsDenseValues", "raggedGather"), o = g(t, "indices", "raggedGather", "int32"), u = {
    paramsNestedSplits: r,
    paramsDenseValues: n,
    indices: o
  }, l = { outputRaggedRank: a }, p = O.runKernel(es, u, l);
  return {
    outputNestedSplits: p.slice(0, p.length - 1),
    outputDenseValues: p[p.length - 1]
  };
}
const fn = /* @__PURE__ */ S({ raggedGather_: kd });
/**
 * @license
 * Copyright 2022 Google LLC.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Id(s, e, t) {
  const a = g(s, "starts", "raggedRange"), r = g(e, "limits", "raggedRange", a.dtype), n = g(t, "deltas", "raggedRange", a.dtype), o = {
    starts: a,
    limits: r,
    deltas: n
  }, u = O.runKernel(ts, o);
  return {
    rtNestedSplits: u[0],
    rtDenseValues: u[1]
  };
}
const yn = /* @__PURE__ */ S({ raggedRange_: Id });
/**
 * @license
 * Copyright 2022 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Dd(s, e, t, a, r) {
  const n = g(s, "shape", "raggedTensorToTensor", "int32"), o = g(e, "values", "raggedTensorToTensor"), u = g(t, "defaultValue", "raggedTensorToTensor", o.dtype), l = a.map((c, d) => g(c, `tensors${d}`, "raggedTensorToTensor", "int32")), p = {
    shape: n,
    values: o,
    defaultValue: u,
    rowPartitionTensors: l
  }, m = { rowPartitionTypes: r };
  return O.runKernel(ss, p, m);
}
const gn = /* @__PURE__ */ S({ raggedTensorToTensor_: Dd });
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function $d(s, e, t) {
  ke(s);
  const a = le(s);
  let r = null;
  if (t == null || t === "float32")
    r = new Float32Array(a);
  else if (t === "int32")
    r = new Int32Array(a);
  else if (t === "bool")
    r = new Uint8Array(a);
  else
    throw new Error(`Unknown data type ${t}`);
  for (let n = 0; n < a; n++)
    r[n] = e();
  return O.makeTensor(r, s, t);
}
const bn = /* @__PURE__ */ S({ rand_: $d });
/**
 * @license
 * Copyright 2017 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Cd = 1e-3, Nn = 0.1;
function zd(s, e, t) {
  return t == null && (t = ft()), Pe(s, e, (a, r) => yt(a, r, t));
}
function ft() {
  return O.backend.floatPrecision() === 32 ? Cd : Nn;
}
function Pe(s, e, t) {
  let a = !0;
  if ((X(s) || X(e)) && (a = !1), X(s) && X(e) && (a = !0), a) {
    const o = s.constructor.name, u = e.constructor.name;
    if (o !== u)
      throw new Error(`Arrays are of different type. Actual: ${o}. Expected: ${u}`);
  }
  if (Array.isArray(s) && Array.isArray(e)) {
    const o = me(s), u = me(e);
    if (!ue(o, u))
      throw new Error(`Arrays have different shapes. Actual: [${o}]. Expected: [${u}]`);
  }
  const r = X(s) ? s : Ct(s), n = X(e) ? e : Ct(e);
  if (r.length !== n.length)
    throw new Error(`Arrays have different lengths actual: ${r.length} vs expected: ${n.length}.
Actual:   ${r}.
Expected: ${n}.`);
  for (let o = 0; o < n.length; ++o) {
    const u = r[o], l = n[o];
    if (!t(u, l))
      throw new Error(`Arrays differ: actual[${o}] = ${u}, expected[${o}] = ${l}.
Actual:   ${r}.
Expected: ${n}.`);
  }
  typeof expect < "u" && expect().nothing();
}
function xd(s, e) {
  s().then(() => e.fail(), () => e()), typeof expect < "u" && expect().nothing();
}
function Ld(s, e) {
  const t = typeof e == "string" || typeof e == "number" || typeof e == "boolean" ? [e] : e;
  return de(s) || de(s[0]) || de(e) || de(e[0]) ? Pe(s, t, (a, r) => a == r) : Pe(s, e, (a, r) => yt(a, r, 0));
}
function Vd(s, e, t) {
  if (t == null && (t = ft()), !yt(s, e, t))
    throw new Error(`Numbers differ: actual === ${s}, expected === ${e}`);
  typeof expect < "u" && expect().nothing();
}
function yt(s, e, t) {
  return !isFinite(s) && !isFinite(e) ? !0 : !(isNaN(s) || isNaN(e) || Math.abs(s - e) > t);
}
function Fd(s, e, t) {
  for (let a = 0; a < s.length; a++)
    if (s[a] < e || s[a] > t)
      throw new Error(`Value out of range:${s[a]} low: ${e}, high: ${t}`);
}
function Pd(s, e) {
  const t = new Float32Array(s), a = new Float32Array(e);
  if (t.length !== a.length)
    throw new Error(`Expected ArrayBuffer to be of length ${a.length}, but it was ${t.length}`);
  for (let r = 0; r < a.length; r++)
    if (t[r] !== a[r])
      throw new Error(`Expected ArrayBuffer value at ${r} to be ${a[r]} but got ${t[r]} instead`);
}
function wn(s) {
  for (let e = 0; e < s.length; e++) {
    const t = s[e];
    Array.isArray(t) ? wn(t) : s[e] = di(t);
  }
  return s;
}
function Rd(s) {
  const e = document.createElement("video");
  return "playsInline" in e && (e.playsInline = !0), e.muted = !0, e.loop = !0, e.style.position = "fixed", e.style.left = "0px", e.style.top = "0px", e.preload = "auto", e.appendChild(s), new Promise((t) => {
    e.addEventListener("loadeddata", (a) => t(e)), e.load();
  });
}
async function jd(s) {
  await s.play(), "requestVideoFrameCallback" in s && await new Promise((e) => {
    s.requestVideoFrameCallback(e);
  });
}
const Bd = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  TEST_EPSILON_FLOAT16: Nn,
  createVideoElement: Rd,
  encodeStrings: wn,
  expectArrayBuffersEqual: Pd,
  expectArraysClose: zd,
  expectArraysEqual: Ld,
  expectNumbersClose: Vd,
  expectPromiseToFail: xd,
  expectValuesInRange: Fd,
  play: jd,
  testEpsilon: ft
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Hd(s, e, t = 1, a = "float32", r) {
  if (ke(s), t == null && (t = 1), a == null && (a = "float32"), a !== "float32" && a !== "int32")
    throw new Error(`Unsupported data type ${a}`);
  const n = new hi(e, t, a, r), o = Xe(s, a);
  for (let u = 0; u < o.values.length; u++)
    o.values[u] = n.nextValue();
  return o.toTensor();
}
const Tn = /* @__PURE__ */ S({ randomGamma_: Hd });
/**
 * @license
 * Copyright 2022 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Wd(s, e, t) {
  if (e != null && e === "bool")
    throw new Error(`Unsupported data type ${e}`);
  return Ze(s, 0, 1, e, t);
}
const Sn = /* @__PURE__ */ S({ randomStandardNormal_: Wd });
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function qd(s) {
  const e = g(s, "x", "reverse");
  return N(e.rank === 1, () => `Error in reverse1D: x must be rank 1 but got rank ${e.rank}.`), se(e, 0);
}
const vn = /* @__PURE__ */ S({ reverse1d_: qd });
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Ud(s, e) {
  const t = g(s, "x", "reverse");
  return N(t.rank === 2, () => `Error in reverse2D: x must be rank 2 but got rank ${t.rank}.`), se(t, e);
}
const On = /* @__PURE__ */ S({ reverse2d_: Ud });
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Gd(s, e) {
  const t = g(s, "x", "reverse");
  return N(t.rank === 3, () => `Error in reverse3D: x must be rank 3 but got rank ${t.rank}.`), se(t, e);
}
const _n = /* @__PURE__ */ S({ reverse3d_: Gd });
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Kd(s, e) {
  const t = g(s, "x", "reverse");
  return N(t.rank === 4, () => `Error in reverse4D: x must be rank 4 but got rank ${t.rank}.`), se(t, e);
}
const An = /* @__PURE__ */ S({ reverse4d_: Kd });
/**
 * @license
 * Copyright 2020 Google Inc. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
async function Jd(s, e) {
  const t = g(s, "x", "setdiff1d"), a = g(e, "y", "setdiff1d");
  N(t.dtype === a.dtype, () => `x and y should have the same dtype, but got x (${t.dtype}) and y (${a.dtype}).`), N(t.rank === 1, () => `x should be 1D tensor, but got x (${t.shape}).`), N(a.rank === 1, () => `y should be 1D tensor, but got y (${a.shape}).`);
  const r = await t.data(), n = await a.data(), o = new Set(n);
  let u = 0;
  for (let m = 0; m < r.length; m++)
    o.has(r[m]) || u++;
  const l = new Le([u], t.dtype), p = new Le([u], "int32");
  for (let m = 0, c = 0; m < r.length; m++)
    o.has(r[m]) || (l.values[c] = r[m], p.values[c] = m, c++);
  return [l.toTensor(), p.toTensor()];
}
const En = Jd;
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function kn(s, e, t) {
  if (Ye(s), e != null && e.length !== 4)
    throw new Error("tensor4d() requires shape to have four numbers");
  const a = me(s, t);
  if (a.length !== 4 && a.length !== 1)
    throw new Error("tensor4d() requires values to be number[][][][] or flat/TypedArray");
  if (a.length === 1 && e == null)
    throw new Error("tensor4d() requires shape to be provided when `values` are a flat array");
  return Me(s, e, a, t);
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function In(s, e, t) {
  if (Ye(s), e != null && e.length !== 5)
    throw new Error("tensor5d() requires shape to have five numbers");
  const a = me(s, t);
  if (a.length !== 5 && a.length !== 1)
    throw new Error("tensor5d() requires values to be number[][][][][] or flat/TypedArray");
  if (a.length === 1 && e == null)
    throw new Error("tensor5d() requires shape to be provided when `values` are a flat array");
  return Me(s, e, a, t);
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Dn(s, e, t) {
  if (Ye(s), e != null && e.length !== 6)
    throw new Error("tensor6d() requires shape to have six numbers");
  const a = me(s, t);
  if (a.length !== 6 && a.length !== 1)
    throw new Error("tensor6d() requires values to be number[][][][][][] or flat/TypedArray");
  if (a.length === 1 && e == null)
    throw new Error("tensor6d() requires shape to be provided when `values` are a flat array");
  return e = e || a, Me(s, e, a, t);
}
/**
 * @license
 * Copyright 2022 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function $n(s, e) {
  return De(s, e, "right");
}
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
async function Qd(s) {
  const e = g(s, "condition", "whereAsync", "bool"), t = await e.data(), a = as(e.shape, t);
  return s !== e && e.dispose(), a;
}
const gt = Qd;
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
async function Xd(s, e, t) {
  const a = g(s, "tensor", "boolMask"), r = g(e, "mask", "boolMask", "bool"), n = t ?? 0, o = r.rank, u = a.shape;
  N(o > 0, () => "mask cannot be scalar"), rs(u.slice(n, n + o), r.shape, "mask's shape must match the first K dimensions of tensor's shape,");
  let l = 1;
  for (let f = n; f < n + o; f++)
    l *= u[f];
  const p = u.slice(0, n).concat([l], u.slice(n + o)), m = v(a, p), c = v(r, [-1]), d = await gt(c), h = et(d, [1]), b = tt(m, h, n);
  return s !== a && a.dispose(), e !== r && r.dispose(), h.dispose(), m.dispose(), c.dispose(), d.dispose(), b;
}
const Cn = Xd;
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Zd(s, e, t, a, r = !0) {
  const n = g(s, "v", "movingAverage"), o = g(e, "x", "movingAverage"), u = g(t, "decay", "movingAverage");
  fi(n, o), N(ue(n.shape, o.shape), () => "Shape mismatch in v and x");
  const l = R(1), p = oe(l, u);
  let m = Y(oe(o, n), p);
  if (r) {
    N(a != null, () => "When using zeroDebias: true, step is required.");
    const c = g(a, "step", "movingAverage");
    m = st(m, oe(l, at(u, c)));
  }
  return G(n, m);
}
const zn = /* @__PURE__ */ S({ movingAverage_: Zd });
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Yd(s, e, t) {
  ke(t);
  const a = g(s, "indices", "scatterND", "int32"), r = g(e, "updates", "scatterND");
  yi(r, a, t);
  const n = { indices: a, updates: r }, o = { shape: t };
  return O.runKernel(ns, n, o);
}
const xn = /* @__PURE__ */ S({ scatterND_: Yd });
function Md(s, e, t, a) {
  if (s.dtype !== "int32")
    throw new Error(`tf.sparseToDense() expects the indices to be int32 type, but the dtype was ${s.dtype}.`);
  if (s.rank > 2)
    throw new Error(`sparseIndices should be a scalar, vector, or matrix, but got shape ${s.shape}.`);
  const r = s.rank > 0 ? s.shape[0] : 1, n = s.rank > 1 ? s.shape[1] : 1;
  if (t.length !== n)
    throw new Error(`outputShape has incorrect number of elements:, ${t.length}, should be: ${n}.`);
  const o = e.size;
  if (!(e.rank === 0 || e.rank === 1 && o === r))
    throw new Error(`sparseValues has incorrect shape ${e.shape}, should be [] or [${r}]`);
  if (e.dtype !== a.dtype)
    throw new Error("sparseValues.dtype must match defaultValues.dtype");
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function eh(s, e, t, a = 0) {
  ke(t);
  const r = g(s, "sparseIndices", "sparseToDense", "int32"), n = g(e, "sparseValues", "sparseToDense", "string_or_numeric"), o = g(a, "defaultValue", "sparseToDense", n.dtype);
  Md(r, n, t, o);
  const u = {
    sparseIndices: r,
    sparseValues: n,
    defaultValue: o
  }, l = { outputShape: t };
  return O.runKernel(is, u, l);
}
const Ln = /* @__PURE__ */ S({ sparseToDense_: eh });
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function th(s, e) {
  const t = g(e, "indices", "gatherND", "int32"), r = { params: g(s, "x", "gatherND", "string_or_numeric"), indices: t };
  return O.runKernel(os, r);
}
const Vn = /* @__PURE__ */ S({ gatherND_: th });
/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
async function sh(s, e, t = 1) {
  const a = g(s, "predictions", "inTopK"), r = g(e, "targets", "inTopK");
  N(a.rank > 1, () => `inTopK() expects the predictions to be of rank 2 or higher, but got ${a.rank}`), N(a.rank - 1 === r.rank, () => `predictions rank should be 1 larger than targets rank, but got predictions rank ${a.rank} and targets rank ${r.rank}`), rs(a.shape.slice(0, a.shape.length - 1), r.shape, "predictions's shape should be align with the targets' shape, except the last dimension.");
  const n = a.shape[a.shape.length - 1];
  N(t > 0 && t <= n, () => `'k' passed to inTopK() must be > 0 && <= the predictions last dimension (${n}), but got ${t}`);
  const o = await a.data(), u = await r.data(), [l, p] = [o.length / n, n], m = gi("bool", l);
  for (let c = 0; c < l; c++) {
    const d = c * p, h = o.subarray(d, d + p), b = [];
    for (let f = 0; f < h.length; f++)
      b.push({ value: h[f], index: f });
    b.sort((f, y) => y.value - f.value), m[c] = 0;
    for (let f = 0; f < t; f++)
      if (b[f].index === u[c]) {
        m[c] = 1;
        break;
      }
  }
  return s !== a && a.dispose(), e !== r && r.dispose(), U(m, r.shape, "bool");
}
const Fn = sh;
/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function ah({ x: s, filter: e, strides: t, pad: a, dataFormat: r = "NHWC", dilations: n = [1, 1], dimRoundingMode: o, bias: u, activation: l = "linear", preluActivationWeights: p, leakyreluAlpha: m }) {
  if (bi(O.state.gradientDepth, l) === !1) {
    let E = rt(s, e, t, a, r, n, o);
    return u != null && (E = G(E, u)), Ni(E, l, p, m);
  }
  const c = g(s, "x", "depthwiseConv2d", "float32"), d = g(e, "filter", "depthwiseConv2d", "float32");
  let h = c, b = !1;
  c.rank === 3 && (b = !0, h = v(c, [1, c.shape[0], c.shape[1], c.shape[2]])), N(h.rank === 4, () => `Error in fused depthwiseConv2d: input must be rank 4, but got rank ${h.rank}.`), N(d.rank === 4, () => `Error in fused depthwiseConv2d: filter must be rank 4, but got rank ${d.rank}.`), N(h.shape[3] === d.shape[2], () => `Error in fused depthwiseConv2d: number of input channels (${h.shape[3]}) must match the inChannels dimension in filter ${d.shape[2]}.`), n == null && (n = [1, 1]), N(wi(t, n), () => `Error in fused depthwiseConv2d: Either strides or dilations must be 1. Got strides ${t} and dilations '${n}'`), Ti("fused depthwiseConv2d", a, o);
  const f = Si(
    h.shape,
    d.shape,
    t,
    n,
    a,
    o,
    !0
    /* depthwise */
  );
  let y;
  u != null && (y = g(u, "bias", "fused conv2d"), [y] = vi(y, c), Oi(f.outShape, y.shape));
  let T;
  p != null && (T = g(p, "prelu weights", "fused depthwiseConv2d"));
  const _ = (E, D) => {
    N(_i(n), () => `Error in gradient of fused depthwiseConv2d: dilation rates greater than 1 are not yet supported. Got dilations '${n}'`);
    const [V, Q, L, j] = D, $e = Ai(E, L, l), Et = Ei(Q.shape, $e, V, t, a, n, o), kt = ki(Q, $e, V.shape, t, a, n, o);
    if (j != null) {
      const li = Ii(y, $e);
      return [Et, kt, li];
    }
    return [Et, kt];
  }, w = {
    x: h,
    filter: d,
    bias: y,
    preluActivationWeights: T
  }, I = {
    strides: t,
    pad: a,
    dataFormat: r,
    dilations: n,
    dimRoundingMode: o,
    activation: l,
    leakyreluAlpha: m
  };
  return u == null ? Ve((D, V, Q) => {
    let L = O.runKernel(Fe, w, I);
    return Q([V, D, L]), b && (L = v(L, [L.shape[1], L.shape[2], L.shape[3]])), { value: L, gradFunc: _ };
  })(h, d) : Ve((D, V, Q, L) => {
    let j = O.runKernel(Fe, w, I);
    return L([V, D, j, Q]), b && (j = v(j, [j.shape[1], j.shape[2], j.shape[3]])), { value: j, gradFunc: _ };
  })(h, d, y);
}
const rh = /* @__PURE__ */ S({ fusedDepthwiseConv2d_: ah });
/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Pn = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  conv2d: Di,
  depthwiseConv2d: rh,
  matMul: $i
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const nh = "model", ih = ".json", oh = ".weights.bin";
function xt(s) {
  return new Promise((e) => setTimeout(e)).then(s);
}
class K {
  constructor(e) {
    if (!x().getBool("IS_BROWSER"))
      throw new Error("browserDownloads() cannot proceed because the current environment is not a browser.");
    e.startsWith(K.URL_SCHEME) && (e = e.slice(K.URL_SCHEME.length)), (e == null || e.length === 0) && (e = nh), this.modelJsonFileName = e + ih, this.weightDataFileName = e + oh;
  }
  async save(e) {
    if (typeof document > "u")
      throw new Error("Browser downloads are not supported in this environment since `document` is not present");
    const t = window.URL.createObjectURL(new Blob([e.weightData], { type: "application/octet-stream" }));
    if (e.modelTopology instanceof ArrayBuffer)
      throw new Error("BrowserDownloads.save() does not support saving model topology in binary formats yet.");
    {
      const a = [{
        paths: ["./" + this.weightDataFileName],
        weights: e.weightSpecs
      }], r = zi(e, a), n = window.URL.createObjectURL(new Blob([JSON.stringify(r)], { type: "application/json" })), o = this.modelJsonAnchor == null ? document.createElement("a") : this.modelJsonAnchor;
      if (o.download = this.modelJsonFileName, o.href = n, await xt(() => o.dispatchEvent(new MouseEvent("click"))), e.weightData != null) {
        const u = this.weightDataAnchor == null ? document.createElement("a") : this.weightDataAnchor;
        u.download = this.weightDataFileName, u.href = t, await xt(() => u.dispatchEvent(new MouseEvent("click")));
      }
      return { modelArtifactsInfo: ps(e) };
    }
  }
}
K.URL_SCHEME = "downloads://";
class uh {
  constructor(e) {
    if (e == null || e.length < 1)
      throw new Error(`When calling browserFiles, at least 1 file is required, but received ${e}`);
    this.jsonFile = e[0], this.weightsFiles = e.slice(1);
  }
  async load() {
    return new Promise((e, t) => {
      const a = new FileReader();
      a.onload = (r) => {
        const n = JSON.parse(r.target.result), o = n.modelTopology;
        if (o == null) {
          t(new Error(`modelTopology field is missing from file ${this.jsonFile.name}`));
          return;
        }
        if (n.weightsManifest == null) {
          t(new Error(`weightManifest field is missing from file ${this.jsonFile.name}`));
          return;
        }
        if (this.weightsFiles.length === 0) {
          e({ modelTopology: o });
          return;
        }
        const l = us(n, (p) => this.loadWeights(p));
        e(l);
      }, a.onerror = (r) => t(`Failed to read model topology and weights manifest JSON from file '${this.jsonFile.name}'. BrowserFiles supports loading Keras-style tf.Model artifacts only.`), a.readAsText(this.jsonFile);
    });
  }
  loadWeights(e) {
    const t = [], a = [];
    for (const o of e)
      t.push(...o.weights), a.push(...o.paths);
    const r = this.checkManifestAndWeightFiles(e), n = a.map((o) => this.loadWeightsFile(o, r[o]));
    return Promise.all(n).then((o) => [t, ls(o)]);
  }
  loadWeightsFile(e, t) {
    return new Promise((a, r) => {
      const n = new FileReader();
      n.onload = (o) => {
        const u = o.target.result;
        a(u);
      }, n.onerror = (o) => r(`Failed to weights data from file of path '${e}'.`), n.readAsArrayBuffer(t);
    });
  }
  /**
   * Check the compatibility between weights manifest and weight files.
   */
  checkManifestAndWeightFiles(e) {
    const t = [], a = this.weightsFiles.map((n) => zt(n.name)), r = {};
    for (const n of e)
      n.paths.forEach((o) => {
        const u = zt(o);
        if (t.indexOf(u) !== -1)
          throw new Error(`Duplicate file basename found in weights manifest: '${u}'`);
        if (t.push(u), a.indexOf(u) === -1)
          throw new Error(`Weight file with basename '${u}' is not provided.`);
        r[o] = this.weightsFiles[a.indexOf(u)];
      });
    if (t.length !== this.weightsFiles.length)
      throw new Error(`Mismatch in the number of files in weights manifest (${t.length}) and the number of weight files provided (${this.weightsFiles.length}).`);
    return r;
  }
}
const lh = (s) => x().getBool("IS_BROWSER") && !Array.isArray(s) && s.startsWith(K.URL_SCHEME) ? ph(s.slice(K.URL_SCHEME.length)) : null;
Ci.registerSaveRouter(lh);
function ph(s = "model") {
  return new K(s);
}
function mh(s) {
  return new uh(s);
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
class Ce {
  constructor(e) {
    this.modelArtifacts = e;
  }
  load() {
    return this.modelArtifacts;
  }
}
class Rn {
  constructor(e) {
    this.saveHandler = e;
  }
  save(e) {
    return this.saveHandler(e);
  }
}
class ch {
  constructor(e) {
    e.load && (this.load = () => Promise.resolve(e.load())), e.save && (this.save = (t) => Promise.resolve(e.save(t)));
  }
}
function dh(s, e, t, a) {
  const r = arguments;
  return new ch(_e(...r));
}
function _e(s, e, t, a) {
  return arguments.length === 1 ? s.modelTopology != null || s.weightSpecs != null ? new Ce(s) : (console.warn("Please call tf.io.fromMemory() with only one argument. The argument should be of type ModelArtifacts. The multi-argument signature of tf.io.fromMemory() has been deprecated and will be removed in a future release."), new Ce({ modelTopology: s })) : (console.warn("Please call tf.io.fromMemory() with only one argument. The argument should be of type ModelArtifacts. The multi-argument signature of tf.io.fromMemory() has been deprecated and will be removed in a future release."), new Ce({
    modelTopology: s,
    weightSpecs: e,
    weightData: t,
    trainingConfig: a
  }));
}
function hh(s) {
  return new Rn(s);
}
function fh(s) {
  return new Rn(s);
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const bt = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  browserFiles: mh,
  browserHTTPRequest: xi,
  concatenateArrayBuffers: ls,
  copyModel: Li,
  decodeWeights: Vi,
  encodeWeights: Fi,
  fromMemory: dh,
  fromMemorySync: _e,
  getLoadHandlers: Pi,
  getModelArtifactsForJSON: us,
  getModelArtifactsForJSONSync: ms,
  getModelArtifactsInfoForJSON: ps,
  getSaveHandlers: Ri,
  getWeightSpecs: cs,
  http: ji,
  isHTTPScheme: Bi,
  listModels: Hi,
  loadWeights: Wi,
  moveModel: qi,
  registerLoadRouter: Ui,
  registerSaveRouter: Gi,
  removeModel: Ki,
  weightsLoaderFactory: Ji,
  withSaveHandler: hh,
  withSaveHandlerSync: fh
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function yh(s, e, t) {
  const a = g(s, "labels", "confusionMatrix"), r = g(e, "predictions", "confusionMatrix");
  N(t == null || t > 0 && Number.isInteger(t), () => `If provided, numClasses must be a positive integer, but got ${t}`), N(a.rank === 1, () => `Expected the rank of labels to be 1, but got ${a.rank}`), N(r.rank === 1, () => `Expected the rank of predictions to be 1, but got ${r.rank}`), N(a.shape[0] === r.shape[0], () => `Mismatch in the number of examples: ${a.shape[0]} vs. ${r.shape[0]}. Labels and predictions should have the same number of elements.`), N(t > 0 && Number.isInteger(t), () => `numClasses is required to be a positive integer, but got ${t}`);
  const n = Se(M(a, "int32"), t), o = Se(M(r, "int32"), t), u = nt(n), l = W(u, o);
  return M(l, "int32");
}
const gh = /* @__PURE__ */ S({ confusionMatrix_: yh });
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const bh = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  confusionMatrix: gh
}, Symbol.toStringTag, { value: "Module" }));
/** @license See the LICENSE file. */
const jn = "4.2.0";
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Nh = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  nonMaxSuppressionV3Impl: Qi,
  nonMaxSuppressionV4Impl: Xi,
  nonMaxSuppressionV5Impl: Zi,
  whereImpl: as
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
function wh(s) {
  return new Yi(s);
}
function Th(s) {
  return new Mi(s);
}
function Sh() {
  return new eo();
}
function vh(s) {
  return new to(s);
}
const Oh = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  maxNorm: wh,
  minMaxNorm: vh,
  nonNeg: Sh,
  unitNorm: Th
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
function _h() {
  return new so();
}
function Ah() {
  return new ao();
}
function Eh(s) {
  return new ro(s);
}
function kh(s) {
  return new no(s);
}
function Ih(s) {
  return new io(s);
}
function Dh(s) {
  return new oo(s);
}
function $h(s) {
  return new uo(s);
}
function Ch(s) {
  return new lo(s);
}
function zh(s) {
  return new po(s);
}
function xh(s) {
  return new mo(s);
}
function Lh(s) {
  return new co(s);
}
function Vh(s) {
  return new ho(s);
}
function Fh(s) {
  return new fo(s);
}
function Ph(s) {
  return new yo(s);
}
function Rh(s) {
  return new go(s);
}
const jh = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  constant: Eh,
  glorotNormal: xh,
  glorotUniform: zh,
  heNormal: Lh,
  heUniform: Vh,
  identity: $h,
  leCunNormal: Fh,
  leCunUniform: Ph,
  ones: Ah,
  orthogonal: Rh,
  randomNormal: Ih,
  randomUniform: kh,
  truncatedNormal: Dh,
  varianceScaling: Ch,
  zeros: _h
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
function Bh(s) {
  return new it(s);
}
function Hh(s) {
  return new ds(s);
}
function Bn(s) {
  return bo(s);
}
function Wh(s, e) {
  No.registerCallbackConstructor(s, e);
}
/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
function qh(s) {
  return new wo(s);
}
function Uh(s) {
  return new To(s);
}
function Gh(s) {
  return new So(s);
}
function Kh(s) {
  return new vo(s);
}
function Jh(s) {
  return new Oo(s);
}
function Qh(s) {
  return new _o(s);
}
function Xh(s) {
  return new Ao(s);
}
function Zh(s) {
  return new Eo(s);
}
function Yh(s) {
  return new ko(s);
}
function Mh(s) {
  return new Io(s);
}
function ef(s) {
  return new Do(s);
}
function tf(s) {
  return new $o(s);
}
function sf(s) {
  return new Co(s);
}
function af(s) {
  return new zo(s);
}
function rf(s) {
  return new xo(s);
}
function nf(s) {
  return new Lo(s);
}
function of(s) {
  return new Vo(s);
}
function uf(s) {
  return new Fo(s);
}
function lf(s) {
  return new Po(s);
}
function pf(s) {
  return new Ro(s);
}
function mf(s) {
  return new jo(s);
}
function cf(s) {
  return new Bo(s);
}
function df(s) {
  return new Ho(s);
}
function hf(s) {
  return new Wo(s);
}
function ff(s) {
  return new qo(s);
}
function yf(s) {
  return new Uo(s);
}
function gf(s) {
  return new Go(s);
}
function bf(s) {
  return new Ko(s);
}
function Nf(s) {
  return new Jo(s);
}
function wf(s) {
  return new Qo(s);
}
function Tf(s) {
  return new Xo(s);
}
function Sf(s) {
  return new Zo(s);
}
function vf(s) {
  return new Yo(s);
}
function Of(s) {
  return new Mo(s);
}
function _f(s) {
  return new eu(s);
}
function Nt(s) {
  return new tu(s);
}
function Af(s) {
  return Nt(s);
}
function Ef(s) {
  return Nt(s);
}
function wt(s) {
  return new su(s);
}
function kf(s) {
  return wt(s);
}
function If(s) {
  return wt(s);
}
function Tt(s) {
  return new au(s);
}
function Df(s) {
  return Tt(s);
}
function $f(s) {
  return Tt(s);
}
function Cf(s) {
  return new ru(s);
}
function zf(s) {
  return new nu(s);
}
function Hn(s) {
  return new iu(s);
}
function Wn(s) {
  return new ou(s);
}
function qn(s) {
  return new uu(s);
}
function Un(s) {
  return new lu(s);
}
function xf(s) {
  return new pu(s);
}
function Lf(s) {
  return new mu(s);
}
function Vf(s) {
  return new cu(s);
}
function Ff(s) {
  return new du(s);
}
function Pf(s) {
  return new hu(s);
}
function Rf(s) {
  return new fu(s);
}
function jf(s) {
  return new yu(s);
}
function Bf(s) {
  return new gu(s);
}
function Hf(s) {
  return new bu(s);
}
function Wf(s) {
  return new ot(s);
}
function qf(s) {
  return new Nu(s);
}
function Uf(s) {
  return new wu(s);
}
function Gf(s) {
  return new Tu(s);
}
const Kf = Hn, Jf = Wn, Qf = qn, Xf = Un;
function Zf(s) {
  return new Su(s);
}
function Yf(s) {
  return new vu(s);
}
function Mf(s) {
  return new Ou(s);
}
function ey(s) {
  return new _u(s);
}
function ty(s) {
  return new Au(s);
}
function sy(s) {
  return new Eu(s);
}
function ay(s) {
  return new ku(s);
}
function ry(s) {
  return new Iu(s);
}
const ny = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  Layer: Du,
  RNN: ot,
  RNNCell: $u,
  activation: of,
  add: yf,
  alphaDropout: Mf,
  average: gf,
  averagePooling1d: Nt,
  averagePooling2d: wt,
  averagePooling3d: Tt,
  avgPool1d: Af,
  avgPool2d: kf,
  avgPool3d: Df,
  avgPooling1d: Ef,
  avgPooling2d: If,
  avgPooling3d: $f,
  batchNormalization: vf,
  bidirectional: Uf,
  categoryEncoding: ry,
  centerCrop: sy,
  concatenate: bf,
  conv1d: Zh,
  conv2d: Yh,
  conv2dTranspose: Mh,
  conv3d: ef,
  conv3dTranspose: tf,
  convLstm2d: Bf,
  convLstm2dCell: Hf,
  cropping2D: af,
  dense: uf,
  depthwiseConv2d: nf,
  dot: Sf,
  dropout: lf,
  elu: Uh,
  embedding: ff,
  flatten: mf,
  gaussianDropout: Yf,
  gaussianNoise: Zf,
  globalAveragePooling1d: Cf,
  globalAveragePooling2d: zf,
  globalMaxPool1d: Kf,
  globalMaxPool2d: Jf,
  globalMaxPooling1d: Hn,
  globalMaxPooling2d: Wn,
  gru: Lf,
  gruCell: Vf,
  input: Bn,
  inputLayer: qh,
  layerNormalization: Of,
  leakyReLU: Kh,
  lstm: Ff,
  lstmCell: Pf,
  masking: ey,
  maxPool1d: Qf,
  maxPool2d: Xf,
  maxPooling1d: qn,
  maxPooling2d: Un,
  maxPooling3d: xf,
  maximum: Nf,
  minimum: wf,
  multiply: Tf,
  permute: hf,
  prelu: Jh,
  reLU: Gh,
  repeatVector: cf,
  rescaling: ty,
  reshape: df,
  resizing: ay,
  rnn: Wf,
  separableConv2d: sf,
  simpleRNN: Rf,
  simpleRNNCell: jf,
  softmax: Qh,
  spatialDropout1d: pf,
  stackedRNNCells: qf,
  thresholdedReLU: Xh,
  timeDistributed: Gf,
  upSampling2d: rf,
  zeroPadding2d: _f
}, Symbol.toStringTag, { value: "Module" }));
function iy(s, e) {
  return Cu(s, e);
}
function oy(s, e) {
  return zu(s, e);
}
function uy(s, e) {
  return xu(s, e);
}
function ly(s, e) {
  return Lu(s, e);
}
function py(s, e) {
  return Vu(s, e);
}
function my(s, e) {
  return Fu(s, e);
}
function cy(s, e) {
  return Pu(s, e);
}
function dy(s, e) {
  return Ru(s, e);
}
function hy(s, e) {
  return ju(s, e);
}
function fy(s, e) {
  return ut(s, e);
}
function yy(s, e) {
  return ut(s, e);
}
function gy(s, e) {
  return ut(s, e);
}
function by(s, e) {
  return lt(s, e);
}
function Ny(s, e) {
  return lt(s, e);
}
function wy(s, e) {
  return lt(s, e);
}
const Ty = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  MAPE: yy,
  MSE: Ny,
  binaryAccuracy: iy,
  binaryCrossentropy: oy,
  categoricalAccuracy: ly,
  categoricalCrossentropy: py,
  cosineProximity: dy,
  mape: gy,
  meanAbsoluteError: hy,
  meanAbsolutePercentageError: fy,
  meanSquaredError: by,
  mse: wy,
  precision: my,
  recall: cy,
  sparseCategoricalAccuracy: uy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
const Sy = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  modelFromJSON: Bu
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
function vy(s) {
  return new Hu(s);
}
function Oy(s) {
  return Wu(s);
}
function _y(s) {
  return qu(s);
}
const Ay = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  l1: Oy,
  l1l2: vy,
  l2: _y
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
class Gn extends Uu {
  constructor() {
    super(...arguments), this.model = null;
  }
  setModel(e) {
    if (!(e instanceof it))
      throw new Error("model must be a LayersModel, not some other Container");
    this.model = e;
  }
}
function fe(s, e) {
  return s < e;
}
function Lt(s, e) {
  return s > e;
}
class Kn extends Gn {
  constructor(e) {
    if (super(), e == null && (e = {}), e.restoreBestWeights)
      throw new Gu("restoreBestWeights = True is not implemented in EarlyStopping yet.");
    this.monitor = e.monitor || "val_loss", this.minDelta = Math.abs(e.minDelta || 0), this.patience = e.patience || 0, this.verbose = e.verbose || 0, this.mode = e.mode || "auto", this.baseline = e.baseline, ["auto", "min", "max"].indexOf(this.mode) === -1 && (console.warn(`EarlyStopping mode '${this.mode}' is invalid. Falling back to mode 'auto'.`), this.mode = "auto"), this.mode === "min" ? this.monitorFunc = fe : this.mode === "max" ? this.monitorFunc = Lt : this.monitor.indexOf("acc") !== -1 ? this.monitorFunc = Lt : this.monitorFunc = fe, this.monitorFunc === fe && (this.minDelta *= -1);
  }
  async onTrainBegin(e) {
    this.wait = 0, this.stoppedEpoch = 0, this.baseline != null ? this.best = this.baseline : this.best = this.monitorFunc === fe ? 1 / 0 : -1 / 0;
  }
  async onEpochEnd(e, t) {
    await Ku(t);
    const a = this.getMonitorValue(t);
    a != null && (this.monitorFunc(a - this.minDelta, this.best) ? (this.best = a, this.wait = 0) : (this.wait++, this.wait >= this.patience && (this.stoppedEpoch = e, this.model.stopTraining = !0)));
  }
  async onTrainEnd(e) {
    this.stoppedEpoch > 0 && this.verbose && console.log(`Epoch ${this.stoppedEpoch}: early stopping.`);
  }
  getMonitorValue(e) {
    e == null && (e = {});
    const t = e[this.monitor];
    return t == null && console.warn(`Metric for EarlyStopping ${this.monitor} is not available. Available metrics are: ${Object.keys(e)}`), t;
  }
}
function Ey(s) {
  return new Kn(s);
}
const ky = { earlyStopping: Ey };
/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const St = {};
function Iy(s, e) {
  const t = {
    tfOpName: s,
    category: "custom",
    inputs: [],
    attrs: [],
    customExecutor: e
  };
  St[s] = t;
}
function Jn(s) {
  return St[s];
}
function Dy(s) {
  delete St[s];
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function i(s, e, t, a, r) {
  const n = e.inputParams[s];
  if (n && n.inputIndexStart !== void 0) {
    const u = n.inputIndexStart, l = n.inputIndexEnd === 0 ? void 0 : n.inputIndexEnd === void 0 ? u + 1 : n.inputIndexEnd;
    if (n.type === "tensor")
      return k(e.inputNames[n.inputIndexStart], t, a, r);
    if (n.type === "tensors")
      return e.inputNames.slice(u, l).map((d) => k(d, t, a, r));
    const p = k(e.inputNames.slice(u)[0], t, a, r), m = p.dataSync();
    return n.type === "number" ? m[0] : Ju(p.shape, m);
  }
  const o = e.attrParams[s];
  return o && o.value;
}
function k(s, e, t, a) {
  const [r, n] = $(s);
  if (a != null) {
    const u = a.getHashTableHandleByName(r);
    if (u != null)
      return u;
  }
  const o = t.currentContextIds.find((u) => !!e[Ae(r, u)]);
  return o !== void 0 ? e[Ae(r, o)][n] : void 0;
}
function $y(s, e, t) {
  return e[Ae(s, t.currentContextId)];
}
function B(s, e) {
  const [t, a, r] = $(s);
  return [
    Ae(t, e && e.currentContextId),
    a,
    r
  ];
}
function Ae(s, e) {
  return e ? `${s}-${e}` : s;
}
function $(s) {
  const e = s.split(":");
  if (e.length === 1)
    return [s, 0, void 0];
  const t = e[0], a = e.length === 3 ? e[1] : void 0, r = Number(e[e.length - 1]);
  return [t, r, a];
}
function be(s, e, t) {
  let a = i("pad", s, e, t);
  if (a === "explicit") {
    a = i("explicitPaddings", s, e, t);
    const r = [[0, 0], [0, 0], [0, 0], [0, 0]];
    for (let n = 0; n < 4; n++)
      r[n][0] = a[n * 2], r[n][1] = a[n * 2 + 1];
    return r;
  }
  return a;
}
function H(s) {
  return s.kept ? s : pt(s);
}
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Cy = [
  {
    tfOpName: "Add",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "AddV2",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "AddN",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        end: 0,
        name: "tensors",
        type: "tensors"
      }
    ]
  },
  {
    tfOpName: "BiasAdd",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Sub",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "RealDiv",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Div",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "DivNoNan",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "FloorDiv",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Mul",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Maximum",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Minimum",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Pow",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "SquaredDifference",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Mod",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "FloorMod",
    category: "arithmetic",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  }
], zy = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: Cy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const xy = [
  {
    tfOpName: "Abs",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Acos",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Asin",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Atan",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Atan2",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "y",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Ceil",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "ClipByValue",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "clipValueMin",
        type: "number"
      },
      {
        start: 2,
        name: "clipValueMax",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Complex",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "real",
        type: "tensor"
      },
      {
        start: 1,
        name: "imag",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "ComplexAbs",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Cos",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Cosh",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Elu",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Exp",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Floor",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Log",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Imag",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "Tout",
        name: "outputType",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Neg",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Real",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "Tout",
        name: "outputType",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Prelu",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "alpha",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Relu",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Relu6",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Selu",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Sigmoid",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Sin",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Sinh",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Sqrt",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Rsqrt",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Square",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Tan",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Tanh",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Sign",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Round",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Expm1",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Log1p",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Reciprocal",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Softplus",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Asinh",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Acosh",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Atanh",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Erf",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Prod",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axes",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "keep_dims",
        name: "keepDims",
        type: "bool",
        notSupported: !0
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LeakyRelu",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "alpha",
        name: "alpha",
        type: "number",
        defaultValue: 0.2
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "IsNan",
    category: "basic_math",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  }
], Ly = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: xy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Vy = [
  {
    tfOpName: "EmptyTensorList",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "elementShape",
        type: "shape"
      },
      {
        start: 1,
        name: "maxNumElements",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "LoopCond",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "pred",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "Switch",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "data",
        type: "tensor"
      },
      {
        start: 1,
        name: "pred",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "Merge",
    category: "control",
    inputs: [
      {
        start: 0,
        end: 0,
        name: "tensors",
        type: "tensors"
      }
    ]
  },
  {
    tfOpName: "Enter",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensor",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "frame_name",
        name: "frameName",
        type: "string"
      },
      {
        tfName: "is_constant",
        name: "isConstant",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "Exit",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensor",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "NextIteration",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensor",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "TensorArrayV3",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "size",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype"
      },
      {
        tfName: "element_shape",
        name: "elementShape",
        type: "shape"
      },
      {
        tfName: "dynamic_size",
        name: "dynamicSize",
        type: "bool"
      },
      {
        tfName: "clear_after_read",
        name: "clearAfterRead",
        type: "bool"
      },
      {
        tfName: "identical_element_shapes",
        name: "identicalElementShapes",
        type: "bool"
      },
      {
        tfName: "tensor_array_name",
        name: "name",
        type: "string"
      }
    ]
  },
  {
    tfOpName: "TensorArrayWriteV3",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorArrayId",
        type: "tensor"
      },
      {
        start: 1,
        name: "index",
        type: "number"
      },
      {
        start: 2,
        name: "tensor",
        type: "tensor"
      },
      {
        start: 3,
        name: "flowIn",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "TensorArrayReadV3",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorArrayId",
        type: "tensor"
      },
      {
        start: 1,
        name: "index",
        type: "number"
      },
      {
        start: 2,
        name: "flowIn",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "TensorArrayGatherV3",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorArrayId",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "number[]"
      },
      {
        start: 2,
        name: "flowIn",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype"
      },
      {
        tfName: "element_shape",
        name: "elementShape",
        type: "shape"
      }
    ]
  },
  {
    tfOpName: "TensorArrayScatterV3",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorArrayId",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "number[]"
      },
      {
        start: 2,
        name: "tensor",
        type: "tensor"
      },
      {
        start: 3,
        name: "flowIn",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorArrayConcatV3",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorArrayId",
        type: "tensor"
      },
      {
        start: 1,
        name: "flowIn",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype"
      },
      {
        tfName: "element_shape_except0",
        name: "elementShapeExcept0",
        type: "shape",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "TensorArraySplitV3",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorArrayId",
        type: "tensor"
      },
      {
        start: 1,
        name: "tensor",
        type: "tensor"
      },
      {
        start: 2,
        name: "lengths",
        type: "number[]"
      },
      {
        start: 3,
        name: "flowIn",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorArraySizeV3",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorArrayId",
        type: "tensor"
      },
      {
        start: 1,
        name: "flowIn",
        type: "number"
      }
    ]
  },
  {
    tfOpName: "TensorArrayCloseV3",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorArrayId",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "StatelessIf",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "cond",
        type: "tensor"
      },
      {
        start: 1,
        end: 0,
        name: "args",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "then_branch",
        name: "thenBranch",
        type: "func"
      },
      {
        tfName: "else_branch",
        name: "elseBranch",
        type: "func"
      }
    ]
  },
  {
    tfOpName: "If",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "cond",
        type: "tensor"
      },
      {
        start: 1,
        end: 0,
        name: "args",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "then_branch",
        name: "thenBranch",
        type: "func"
      },
      {
        tfName: "else_branch",
        name: "elseBranch",
        type: "func"
      }
    ]
  },
  {
    tfOpName: "StatelessWhile",
    category: "control",
    inputs: [
      {
        start: 0,
        end: 0,
        name: "args",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "cond",
        name: "cond",
        type: "func"
      },
      {
        tfName: "body",
        name: "body",
        type: "func"
      }
    ]
  },
  {
    tfOpName: "While",
    category: "control",
    inputs: [
      {
        start: 0,
        end: 0,
        name: "args",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "cond",
        name: "cond",
        type: "func"
      },
      {
        tfName: "body",
        name: "body",
        type: "func"
      }
    ]
  },
  {
    tfOpName: "TensorListScatter",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensor",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "number[]"
      },
      {
        start: 2,
        name: "elementShape",
        type: "shape"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListScatterV2",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensor",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "number[]"
      },
      {
        start: 2,
        name: "elementShape",
        type: "shape"
      },
      {
        start: 3,
        name: "numElements",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListGather",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "number[]"
      },
      {
        start: 2,
        name: "elementShape",
        type: "shape"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListGetItem",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      },
      {
        start: 1,
        name: "index",
        type: "number"
      },
      {
        start: 2,
        name: "elementShape",
        type: "shape"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListSetItem",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      },
      {
        start: 1,
        name: "index",
        type: "number"
      },
      {
        start: 2,
        name: "tensor",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListReserve",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "elementShape",
        type: "shape"
      },
      {
        start: 1,
        name: "numElements",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListFromTensor",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensor",
        type: "tensor"
      },
      {
        start: 1,
        name: "elementShape",
        type: "shape"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListStack",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      },
      {
        start: 1,
        name: "elementShape",
        type: "shape"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      },
      {
        tfName: "num_elements",
        name: "numElements",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListSplit",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensor",
        type: "tensor"
      },
      {
        start: 1,
        name: "elementShape",
        type: "shape"
      },
      {
        start: 2,
        name: "lengths",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListConcat",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "element_shape",
        name: "elementShape",
        type: "shape"
      },
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListConcatV2",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "element_shape",
        name: "elementShape",
        type: "shape"
      },
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListPopBack",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      },
      {
        start: 1,
        name: "elementShape",
        type: "shape"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListPushBack",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      },
      {
        start: 1,
        name: "tensor",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "element_dtype",
        name: "elementDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TensorListLength",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "TensorListResize",
    category: "control",
    inputs: [
      {
        start: 0,
        name: "tensorListId",
        type: "tensor"
      },
      {
        start: 1,
        name: "size",
        type: "number"
      }
    ]
  }
], Fy = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: Vy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Py = [
  {
    tfOpName: "AvgPool",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        notSupported: !0
      },
      {
        tfName: "ksize",
        name: "kernelSize",
        type: "number[]"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "MaxPool",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        notSupported: !0
      },
      {
        tfName: "ksize",
        name: "kernelSize",
        type: "number[]"
      },
      {
        tfName: "explicit_paddings",
        name: "explicitPaddings",
        type: "number[]",
        defaultValue: [],
        notSupported: !0
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "MaxPoolWithArgmax",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "ksize",
        name: "kernelSize",
        type: "number[]"
      },
      {
        tfName: "include_batch_in_index",
        name: "includeBatchInIndex",
        type: "bool"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "AvgPool3D",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        notSupported: !0
      },
      {
        tfName: "ksize",
        name: "kernelSize",
        type: "number[]"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "MaxPool3D",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        notSupported: !0
      },
      {
        tfName: "ksize",
        name: "kernelSize",
        type: "number[]"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Conv1D",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "filter",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "stride",
        name: "stride",
        type: "number"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        defaultValue: "NWC"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "dilation",
        name: "dilation",
        type: "number",
        defaultValue: 1
      }
    ]
  },
  {
    tfOpName: "Conv2D",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "filter",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "useCudnnOnGpu",
        name: "useCudnnOnGpu",
        type: "bool"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        defaultValue: "NHWC"
      },
      {
        tfName: "explicit_paddings",
        name: "explicitPaddings",
        type: "number[]",
        defaultValue: []
      },
      {
        tfName: "dilations",
        name: "dilations",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "_FusedConv2D",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "filter",
        type: "tensor"
      },
      {
        start: 2,
        end: 0,
        name: "args",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "num_args",
        name: "numArgs",
        type: "number"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "explicit_paddings",
        name: "explicitPaddings",
        type: "number[]",
        defaultValue: []
      },
      {
        tfName: "use_cudnn_on_gpu",
        name: "useCudnnOnGpu",
        type: "bool",
        defaultValue: !0
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        defaultValue: "NHWC"
      },
      {
        tfName: "dilations",
        name: "dilations",
        type: "number[]",
        defaultValue: [
          1,
          1,
          1,
          1
        ]
      },
      {
        tfName: "fused_ops",
        name: "fusedOps",
        type: "string[]",
        defaultValue: []
      },
      {
        tfName: "epsilon",
        name: "epsilon",
        type: "number",
        defaultValue: 1e-4
      },
      {
        tfName: "leakyrelu_alpha",
        name: "leakyreluAlpha",
        type: "number",
        defaultValue: 0.2
      }
    ]
  },
  {
    tfOpName: "Conv2DBackpropInput",
    category: "convolution",
    inputs: [
      {
        start: 2,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "filter",
        type: "tensor"
      },
      {
        start: 0,
        name: "outputShape",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        notSupported: !0
      },
      {
        tfName: "explicit_paddings",
        name: "explicitPaddings",
        type: "number[]",
        defaultValue: []
      },
      {
        tfName: "dilations",
        name: "dilations",
        type: "number[]",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "DepthwiseConv2d",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "input",
        type: "tensor"
      },
      {
        start: 1,
        name: "filter",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        defaultValue: "NHWC"
      },
      {
        tfName: "explicit_paddings",
        name: "explicitPaddings",
        type: "number[]",
        defaultValue: []
      },
      {
        tfName: "dilations",
        name: "dilations",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "DepthwiseConv2dNative",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "input",
        type: "tensor"
      },
      {
        start: 1,
        name: "filter",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        defaultValue: "NHWC"
      },
      {
        tfName: "explicit_paddings",
        name: "explicitPaddings",
        type: "number[]",
        defaultValue: []
      },
      {
        tfName: "dilations",
        name: "dilations",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "FusedDepthwiseConv2dNative",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "filter",
        type: "tensor"
      },
      {
        start: 2,
        end: 0,
        name: "args",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "num_args",
        name: "numArgs",
        type: "number"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        defaultValue: "NHWC"
      },
      {
        tfName: "dilations",
        name: "dilations",
        type: "number[]",
        defaultValue: [
          1,
          1,
          1,
          1
        ]
      },
      {
        tfName: "fused_ops",
        name: "fusedOps",
        type: "string[]",
        defaultValue: []
      },
      {
        tfName: "explicit_paddings",
        name: "explicitPaddings",
        type: "number[]",
        defaultValue: []
      }
    ]
  },
  {
    tfOpName: "Conv3D",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "filter",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        defaultValue: "NHWC"
      },
      {
        tfName: "dilations",
        name: "dilations",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "Dilation2D",
    category: "convolution",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "filter",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "strides",
        name: "strides",
        type: "number[]"
      },
      {
        tfName: "rates",
        name: "dilations",
        type: "number[]"
      },
      {
        tfName: "padding",
        name: "pad",
        type: "string"
      }
    ]
  }
], Ry = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: Py
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const jy = [
  {
    tfOpName: "Fill",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "shape",
        type: "number[]"
      },
      {
        start: 1,
        name: "value",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "LinSpace",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "start",
        type: "number"
      },
      {
        start: 1,
        name: "stop",
        type: "number"
      },
      {
        start: 2,
        name: "num",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "OneHot",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "indices",
        type: "tensor"
      },
      {
        start: 1,
        name: "depth",
        type: "number"
      },
      {
        start: 2,
        name: "onValue",
        type: "number",
        defaultValue: 1
      },
      {
        start: 3,
        name: "offValue",
        type: "number",
        defaultValue: 0
      }
    ],
    attrs: [
      {
        tfName: "axis",
        name: "axis",
        type: "number",
        notSupported: !0
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "Ones",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "shape",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "OnesLike",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "RandomStandardNormal",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "shape",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "seed",
        name: "seed",
        type: "number",
        defaultValue: 0
      },
      {
        tfName: "seed2",
        name: "seed2",
        type: "number",
        defaultValue: 0,
        notSupported: !0
      },
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype"
      },
      {
        tfName: "T",
        name: "T",
        type: "number",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "RandomUniform",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "shape",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "minval",
        name: "minval",
        type: "number",
        defaultValue: 0
      },
      {
        tfName: "maxval",
        name: "maxval",
        type: "number",
        defaultValue: 1
      },
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype"
      },
      {
        tfName: "seed",
        name: "seed",
        type: "number",
        defaultValue: 0
      },
      {
        tfName: "seed2",
        name: "seed2",
        type: "number",
        defaultValue: 0,
        notSupported: !0
      },
      {
        tfName: "T",
        name: "T",
        type: "number",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Range",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "start",
        type: "number"
      },
      {
        start: 1,
        name: "stop",
        type: "number"
      },
      {
        start: 2,
        name: "step",
        type: "number",
        defaultValue: 0
      }
    ],
    attrs: [
      {
        tfName: "Tidx",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "TruncatedNormal",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "shape",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "means",
        name: "mean",
        type: "number",
        defaultValue: 0
      },
      {
        tfName: "stddev",
        name: "stdDev",
        type: "number",
        defaultValue: 1
      },
      {
        tfName: "seed",
        name: "seed",
        type: "number"
      },
      {
        tfName: "seed2",
        name: "seed2",
        type: "number",
        defaultValue: 0,
        notSupported: !0
      },
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype"
      },
      {
        tfName: "T",
        name: "T",
        type: "number",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Zeros",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "shape",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "ZerosLike",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "Multinomial",
    category: "creation",
    inputs: [
      {
        start: 0,
        name: "logits",
        type: "tensor"
      },
      {
        start: 1,
        name: "numSamples",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "seed",
        name: "seed",
        type: "number"
      },
      {
        tfName: "seed2",
        name: "seed2",
        type: "number"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype"
      },
      {
        tfName: "output_dtype",
        name: "output_dtype",
        type: "dtype"
      }
    ]
  }
], By = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: jy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Hy = [
  {
    tfOpName: "NonMaxSuppressionV2",
    category: "dynamic",
    inputs: [
      {
        start: 0,
        name: "boxes",
        type: "tensor"
      },
      {
        start: 1,
        name: "scores",
        type: "tensor"
      },
      {
        start: 2,
        name: "maxOutputSize",
        type: "number"
      },
      {
        start: 3,
        name: "iouThreshold",
        type: "number"
      }
    ]
  },
  {
    tfOpName: "NonMaxSuppressionV3",
    category: "dynamic",
    inputs: [
      {
        start: 0,
        name: "boxes",
        type: "tensor"
      },
      {
        start: 1,
        name: "scores",
        type: "tensor"
      },
      {
        start: 2,
        name: "maxOutputSize",
        type: "number"
      },
      {
        start: 3,
        name: "iouThreshold",
        type: "number"
      },
      {
        start: 4,
        name: "scoreThreshold",
        type: "number"
      }
    ]
  },
  {
    tfOpName: "NonMaxSuppressionV4",
    category: "dynamic",
    inputs: [
      {
        start: 0,
        name: "boxes",
        type: "tensor"
      },
      {
        start: 1,
        name: "scores",
        type: "tensor"
      },
      {
        start: 2,
        name: "maxOutputSize",
        type: "number"
      },
      {
        start: 3,
        name: "iouThreshold",
        type: "number"
      },
      {
        start: 4,
        name: "scoreThreshold",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "T_threshold",
        name: "threshold",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "pad_to_max_output_size",
        name: "padToMaxOutputSize",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "NonMaxSuppressionV5",
    category: "dynamic",
    inputs: [
      {
        start: 0,
        name: "boxes",
        type: "tensor"
      },
      {
        start: 1,
        name: "scores",
        type: "tensor"
      },
      {
        start: 2,
        name: "maxOutputSize",
        type: "number"
      },
      {
        start: 3,
        name: "iouThreshold",
        type: "number"
      },
      {
        start: 4,
        name: "scoreThreshold",
        type: "number"
      },
      {
        start: 5,
        name: "softNmsSigma",
        type: "number"
      }
    ]
  },
  {
    tfOpName: "Where",
    category: "dynamic",
    inputs: [
      {
        start: 0,
        name: "condition",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "ListDiff",
    category: "dynamic",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "y",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  }
], Wy = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: Hy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const qy = [
  {
    tfOpName: "LowerBound",
    category: "evaluation",
    inputs: [
      {
        start: 0,
        name: "sortedSequence",
        type: "tensor"
      },
      {
        start: 1,
        name: "values",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "TopKV2",
    category: "evaluation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "k",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "sorted",
        name: "sorted",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "UpperBound",
    category: "evaluation",
    inputs: [
      {
        start: 0,
        name: "sortedSequence",
        type: "tensor"
      },
      {
        start: 1,
        name: "values",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "Unique",
    category: "evaluation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "UniqueV2",
    category: "evaluation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number"
      }
    ]
  }
], Uy = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: qy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Gy = [
  {
    tfOpName: "PlaceholderWithDefault",
    category: "graph",
    inputs: [
      {
        start: 0,
        name: "default",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "shape",
        name: "shape",
        type: "shape"
      },
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "Placeholder",
    category: "graph",
    attrs: [
      {
        tfName: "shape",
        name: "shape",
        type: "shape"
      },
      {
        tfName: "dtype",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "Const",
    category: "graph"
  },
  {
    tfOpName: "Identity",
    category: "graph",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "IdentityN",
    category: "graph",
    inputs: [
      {
        start: 0,
        end: 0,
        name: "x",
        type: "tensors"
      }
    ]
  },
  {
    tfOpName: "Snapshot",
    category: "graph",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "Rank",
    category: "graph",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "Size",
    category: "graph",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "Shape",
    category: "graph",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "ShapeN",
    category: "graph",
    inputs: [
      {
        start: 0,
        end: 0,
        name: "x",
        type: "tensors"
      }
    ]
  },
  {
    tfOpName: "Print",
    category: "graph",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "data",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "message",
        name: "message",
        type: "string"
      },
      {
        tfName: "first_n",
        name: "firstN",
        type: "number",
        notSupported: !0
      },
      {
        tfName: "summarize",
        name: "summarize",
        type: "number",
        defaultValue: 3
      }
    ]
  },
  {
    tfOpName: "NoOp",
    category: "graph",
    inputs: []
  },
  {
    tfOpName: "StopGradient",
    category: "graph",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "FakeQuantWithMinMaxVars",
    category: "graph",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "min",
        name: "min",
        type: "number"
      },
      {
        tfName: "max",
        name: "max",
        type: "number"
      }
    ]
  }
], Ky = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: Gy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Jy = [
  {
    tfOpName: "HashTable",
    category: "hash_table",
    inputs: [],
    attrs: [
      {
        tfName: "shared_name",
        name: "sharedName",
        type: "string"
      },
      {
        tfName: "use_node_name_sharing",
        name: "useNodeNameSharing",
        type: "bool"
      },
      {
        tfName: "key_dtype",
        name: "keyDType",
        type: "dtype"
      },
      {
        tfName: "value_dtype",
        name: "valueDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "HashTableV2",
    category: "hash_table",
    inputs: [],
    attrs: [
      {
        tfName: "shared_name",
        name: "sharedName",
        type: "string"
      },
      {
        tfName: "use_node_name_sharing",
        name: "useNodeNameSharing",
        type: "bool"
      },
      {
        tfName: "key_dtype",
        name: "keyDType",
        type: "dtype"
      },
      {
        tfName: "value_dtype",
        name: "valueDType",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "LookupTableImport",
    category: "hash_table",
    inputs: [
      {
        start: 0,
        name: "tableHandle",
        type: "tensor"
      },
      {
        start: 1,
        name: "keys",
        type: "tensor"
      },
      {
        start: 2,
        name: "values",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "Tin",
        name: "tIn",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "Tout",
        name: "tOut",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LookupTableImportV2",
    category: "hash_table",
    inputs: [
      {
        start: 0,
        name: "tableHandle",
        type: "tensor"
      },
      {
        start: 1,
        name: "keys",
        type: "tensor"
      },
      {
        start: 2,
        name: "values",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "Tin",
        name: "tIn",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "Tout",
        name: "tOut",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LookupTableFind",
    category: "hash_table",
    inputs: [
      {
        start: 0,
        name: "tableHandle",
        type: "tensor"
      },
      {
        start: 1,
        name: "keys",
        type: "tensor"
      },
      {
        start: 2,
        name: "defaultValue",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "Tin",
        name: "tIn",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "Tout",
        name: "tOut",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LookupTableFindV2",
    category: "hash_table",
    inputs: [
      {
        start: 0,
        name: "tableHandle",
        type: "tensor"
      },
      {
        start: 1,
        name: "keys",
        type: "tensor"
      },
      {
        start: 2,
        name: "defaultValue",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "Tin",
        name: "tIn",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "Tout",
        name: "tOut",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LookupTableSize",
    category: "hash_table",
    inputs: [
      {
        start: 0,
        name: "tableHandle",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "LookupTableSizeV2",
    category: "hash_table",
    inputs: [
      {
        start: 0,
        name: "tableHandle",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "InitializeTable",
    category: "hash_table",
    inputs: [
      {
        start: 0,
        name: "tableHandle",
        type: "tensor"
      },
      {
        start: 1,
        name: "keys",
        type: "tensor"
      },
      {
        start: 2,
        name: "values",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "InitializeTableV2",
    category: "hash_table",
    inputs: [
      {
        start: 0,
        name: "tableHandle",
        type: "tensor"
      },
      {
        start: 1,
        name: "keys",
        type: "tensor"
      },
      {
        start: 2,
        name: "values",
        type: "tensor"
      }
    ]
  }
], Qy = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: Jy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Xy = [
  {
    tfOpName: "ResizeBilinear",
    category: "image",
    inputs: [
      {
        start: 0,
        name: "images",
        type: "tensor"
      },
      {
        start: 1,
        name: "size",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "align_corners",
        name: "alignCorners",
        type: "bool"
      },
      {
        tfName: "half_pixel_centers",
        name: "halfPixelCenters",
        type: "bool"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "ResizeNearestNeighbor",
    category: "image",
    inputs: [
      {
        start: 0,
        name: "images",
        type: "tensor"
      },
      {
        start: 1,
        name: "size",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "align_corners",
        name: "alignCorners",
        type: "bool"
      },
      {
        tfName: "half_pixel_centers",
        name: "halfPixelCenters",
        type: "bool"
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "CropAndResize",
    category: "image",
    inputs: [
      {
        start: 0,
        name: "image",
        type: "tensor"
      },
      {
        start: 1,
        name: "boxes",
        type: "tensor"
      },
      {
        start: 2,
        name: "boxInd",
        type: "tensor"
      },
      {
        start: 3,
        name: "cropSize",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "method",
        name: "method",
        type: "string"
      },
      {
        tfName: "extrapolation_value",
        name: "extrapolationValue",
        type: "number"
      }
    ]
  },
  {
    tfOpName: "ImageProjectiveTransformV3",
    category: "image",
    inputs: [
      {
        start: 0,
        name: "images",
        type: "tensor"
      },
      {
        start: 1,
        name: "transforms",
        type: "tensor"
      },
      {
        start: 2,
        name: "outputShape",
        type: "number[]"
      },
      {
        start: 3,
        name: "fillValue",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "interpolation",
        name: "interpolation",
        type: "string"
      },
      {
        tfName: "fill_mode",
        name: "fillMode",
        type: "string"
      }
    ]
  }
], Zy = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: Xy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Yy = [
  {
    tfOpName: "Equal",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "NotEqual",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Greater",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "GreaterEqual",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Less",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LessEqual",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LogicalAnd",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LogicalNot",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LogicalOr",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Select",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "condition",
        type: "tensor"
      },
      {
        start: 1,
        name: "a",
        type: "tensor"
      },
      {
        start: 2,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "SelectV2",
    category: "logical",
    inputs: [
      {
        start: 0,
        name: "condition",
        type: "tensor"
      },
      {
        start: 1,
        name: "a",
        type: "tensor"
      },
      {
        start: 2,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  }
], My = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: Yy
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const eg = [
  {
    tfOpName: "_FusedMatMul",
    category: "matrices",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      },
      {
        start: 2,
        end: 0,
        name: "args",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "num_args",
        name: "numArgs",
        type: "number"
      },
      {
        tfName: "fused_ops",
        name: "fusedOps",
        type: "string[]",
        defaultValue: []
      },
      {
        tfName: "epsilon",
        name: "epsilon",
        type: "number",
        defaultValue: 1e-4
      },
      {
        tfName: "transpose_a",
        name: "transposeA",
        type: "bool",
        defaultValue: !1
      },
      {
        tfName: "transpose_b",
        name: "transposeB",
        type: "bool",
        defaultValue: !1
      },
      {
        tfName: "leakyrelu_alpha",
        name: "leakyreluAlpha",
        type: "number",
        defaultValue: 0.2
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "MatMul",
    category: "matrices",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "transpose_a",
        name: "transposeA",
        type: "bool",
        defaultValue: !1
      },
      {
        tfName: "transpose_b",
        name: "transposeB",
        type: "bool",
        defaultValue: !1
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "BatchMatMul",
    category: "matrices",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "adj_x",
        name: "transposeA",
        type: "bool",
        defaultValue: !1
      },
      {
        tfName: "adj_y",
        name: "transposeB",
        type: "bool",
        defaultValue: !1
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "BatchMatMulV2",
    category: "matrices",
    inputs: [
      {
        start: 0,
        name: "a",
        type: "tensor"
      },
      {
        start: 1,
        name: "b",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "adj_x",
        name: "transposeA",
        type: "bool",
        defaultValue: !1
      },
      {
        tfName: "adj_y",
        name: "transposeB",
        type: "bool",
        defaultValue: !1
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Transpose",
    category: "matrices",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "perm",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Einsum",
    category: "matrices",
    inputs: [
      {
        start: 0,
        end: 0,
        name: "tensors",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "equation",
        name: "equation",
        type: "string"
      },
      {
        tfName: "N",
        name: "n",
        type: "number",
        defaultValue: 2
      },
      {
        tfName: "T",
        name: "dtype",
        type: "dtype"
      }
    ]
  }
], tg = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: eg
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const sg = [
  {
    tfOpName: "EuclideanNorm",
    category: "normalization",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "keep_dims",
        name: "keepDims",
        type: "bool",
        defaultValue: !1
      }
    ]
  },
  {
    tfOpName: "FusedBatchNorm",
    category: "normalization",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "scale",
        type: "tensor"
      },
      {
        start: 2,
        name: "offset",
        type: "tensor"
      },
      {
        start: 3,
        name: "mean",
        type: "tensor"
      },
      {
        start: 4,
        name: "variance",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "epsilon",
        name: "epsilon",
        type: "number",
        defaultValue: 1e-3
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "FusedBatchNormV2",
    category: "normalization",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "scale",
        type: "tensor"
      },
      {
        start: 2,
        name: "offset",
        type: "tensor"
      },
      {
        start: 3,
        name: "mean",
        type: "tensor"
      },
      {
        start: 4,
        name: "variance",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "epsilon",
        name: "epsilon",
        type: "number",
        defaultValue: 1e-3
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "FusedBatchNormV3",
    category: "normalization",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "scale",
        type: "tensor"
      },
      {
        start: 2,
        name: "offset",
        type: "tensor"
      },
      {
        start: 3,
        name: "mean",
        type: "tensor"
      },
      {
        start: 4,
        name: "variance",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "epsilon",
        name: "epsilon",
        type: "number",
        defaultValue: 1e-3
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "LRN",
    category: "normalization",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "depth_radius",
        name: "radius",
        type: "number",
        defaultValue: 5
      },
      {
        tfName: "bias",
        name: "bias",
        type: "number",
        defaultValue: 1
      },
      {
        tfName: "alpha",
        name: "alpha",
        type: "number",
        defaultValue: 1
      },
      {
        tfName: "beta",
        name: "beta",
        type: "number",
        defaultValue: 0.5
      }
    ]
  },
  {
    tfOpName: "Softmax",
    category: "normalization",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "LogSoftmax",
    category: "normalization",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "SparseToDense",
    category: "normalization",
    inputs: [
      {
        start: 0,
        name: "sparseIndices",
        type: "tensor"
      },
      {
        start: 1,
        name: "outputShape",
        type: "number[]"
      },
      {
        start: 2,
        name: "sparseValues",
        type: "tensor"
      },
      {
        start: 3,
        name: "defaultValue",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "validate_indices",
        name: "validateIndices",
        type: "bool",
        defaultValue: !0,
        notSupported: !0
      }
    ]
  }
], ag = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: sg
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const rg = [
  {
    tfOpName: "Bincount",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "size",
        type: "number"
      },
      {
        start: 2,
        name: "weights",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "DenseBincount",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "size",
        type: "number"
      },
      {
        start: 2,
        name: "weights",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "binary_output",
        name: "binaryOutput",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "Max",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "keep_dims",
        name: "keepDims",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "Mean",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "keep_dims",
        name: "keepDims",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "Min",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "keep_dims",
        name: "keepDims",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "Sum",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "keep_dims",
        name: "keepDims",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "All",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "keep_dims",
        name: "keepDims",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "Any",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "keep_dims",
        name: "keepDims",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "ArgMax",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number"
      }
    ]
  },
  {
    tfOpName: "ArgMin",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number"
      }
    ]
  },
  {
    tfOpName: "Prod",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "keep_dims",
        name: "keepDims",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "Cumprod",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "exclusive",
        name: "exclusive",
        type: "bool"
      },
      {
        tfName: "reverse",
        name: "reverse",
        type: "bool"
      }
    ]
  },
  {
    tfOpName: "Cumsum",
    category: "reduction",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "exclusive",
        name: "exclusive",
        type: "bool"
      },
      {
        tfName: "reverse",
        name: "reverse",
        type: "bool"
      }
    ]
  }
], ng = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: rg
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const ig = [
  {
    tfOpName: "ConcatV2",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        end: -1,
        name: "tensors",
        type: "tensors"
      },
      {
        start: -1,
        name: "axis",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "N",
        name: "n",
        type: "number",
        defaultValue: 2
      }
    ]
  },
  {
    tfOpName: "Concat",
    category: "slice_join",
    inputs: [
      {
        start: 1,
        end: 0,
        name: "tensors",
        type: "tensors"
      },
      {
        start: 0,
        name: "axis",
        type: "number"
      }
    ],
    attrs: [
      {
        tfName: "N",
        name: "n",
        type: "number",
        defaultValue: 2
      }
    ]
  },
  {
    tfOpName: "GatherV2",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "tensor"
      },
      {
        start: 2,
        name: "axis",
        type: "number",
        defaultValue: 0
      }
    ],
    attrs: [
      {
        tfName: "batch_dims",
        name: "batchDims",
        type: "number",
        defaultValue: 0
      }
    ]
  },
  {
    tfOpName: "Gather",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "validate_indices",
        name: "validateIndices",
        type: "bool",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Reverse",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "dims",
        type: "bool[]"
      }
    ]
  },
  {
    tfOpName: "ReverseV2",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "Slice",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "begin",
        type: "number[]"
      },
      {
        start: 2,
        name: "size",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "StridedSlice",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "begin",
        type: "number[]"
      },
      {
        start: 2,
        name: "end",
        type: "number[]"
      },
      {
        start: 3,
        name: "strides",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "begin_mask",
        name: "beginMask",
        type: "number",
        defaultValue: 0
      },
      {
        tfName: "end_mask",
        name: "endMask",
        type: "number",
        defaultValue: 0
      },
      {
        tfName: "new_axis_mask",
        name: "newAxisMask",
        type: "number",
        defaultValue: 0
      },
      {
        tfName: "ellipsis_mask",
        name: "ellipsisMask",
        type: "number",
        defaultValue: 0
      },
      {
        tfName: "shrink_axis_mask",
        name: "shrinkAxisMask",
        type: "number",
        defaultValue: 0
      }
    ]
  },
  {
    tfOpName: "Pack",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        end: 0,
        name: "tensors",
        type: "tensors"
      }
    ],
    attrs: [
      {
        tfName: "axis",
        name: "axis",
        type: "number",
        defaultValue: 0
      }
    ]
  },
  {
    tfOpName: "Unpack",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "tensor",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "axis",
        name: "axis",
        type: "number",
        defaultValue: 0
      },
      {
        tfName: "num",
        name: "num",
        type: "number",
        defaultValue: 0,
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "Tile",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "reps",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "Split",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "axis",
        type: "number",
        defaultValue: 0
      },
      {
        start: 1,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "num_split",
        name: "numOrSizeSplits",
        type: "number",
        defaultValue: 1
      }
    ]
  },
  {
    tfOpName: "SplitV",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "numOrSizeSplits",
        type: "number[]"
      },
      {
        start: 2,
        name: "axis",
        type: "number",
        defaultValue: 0
      }
    ]
  },
  {
    tfOpName: "ScatterNd",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "indices",
        type: "tensor"
      },
      {
        start: 1,
        name: "values",
        type: "tensor"
      },
      {
        start: 2,
        name: "shape",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "GatherNd",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "SparseToDense",
    category: "slice_join",
    inputs: [
      {
        start: 0,
        name: "sparseIndices",
        type: "tensor"
      },
      {
        start: 1,
        name: "outputShape",
        type: "number[]"
      },
      {
        start: 2,
        name: "sparseValues",
        type: "tensor"
      },
      {
        start: 3,
        name: "defaultValue",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "validate_indices",
        name: "validateIndices",
        type: "bool",
        defaultValue: !1,
        notSupported: !0
      }
    ]
  }
], og = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: ig
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const ug = [
  {
    tfOpName: "SparseFillEmptyRows",
    category: "sparse",
    inputs: [
      {
        start: 0,
        name: "indices",
        type: "tensor"
      },
      {
        start: 1,
        name: "values",
        type: "tensor"
      },
      {
        start: 2,
        name: "denseShape",
        type: "tensor"
      },
      {
        start: 3,
        name: "defaultValue",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "SparseReshape",
    category: "sparse",
    inputs: [
      {
        start: 0,
        name: "inputIndices",
        type: "tensor"
      },
      {
        start: 1,
        name: "inputShape",
        type: "tensor"
      },
      {
        start: 2,
        name: "newShape",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "T",
        name: "dtype",
        type: "dtype",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "SparseSegmentMean",
    category: "sparse",
    inputs: [
      {
        start: 0,
        name: "data",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "tensor"
      },
      {
        start: 2,
        name: "segmentIds",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "SparseSegmentSum",
    category: "sparse",
    inputs: [
      {
        start: 0,
        name: "data",
        type: "tensor"
      },
      {
        start: 1,
        name: "indices",
        type: "tensor"
      },
      {
        start: 2,
        name: "segmentIds",
        type: "tensor"
      }
    ]
  }
], lg = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: ug
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const pg = [
  {
    tfOpName: "FFT",
    category: "spectral",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "IFFT",
    category: "spectral",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ]
  },
  {
    tfOpName: "RFFT",
    category: "spectral",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "fft_length",
        type: "number",
        notSupported: !0
      }
    ]
  },
  {
    tfOpName: "IRFFT",
    category: "spectral",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "fft_length",
        type: "number",
        notSupported: !0
      }
    ]
  }
], mg = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: pg
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const cg = [
  {
    tfOpName: "StringNGrams",
    category: "string",
    inputs: [
      {
        start: 0,
        name: "data",
        type: "tensor"
      },
      {
        start: 1,
        name: "dataSplits",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "separator",
        name: "separator",
        type: "string"
      },
      {
        tfName: "ngram_widths",
        name: "nGramWidths",
        type: "number[]"
      },
      {
        tfName: "left_pad",
        name: "leftPad",
        type: "string"
      },
      {
        tfName: "right_pad",
        name: "rightPad",
        type: "string"
      },
      {
        tfName: "pad_width",
        name: "padWidth",
        type: "number"
      },
      {
        tfName: "preserve_short_sequences",
        name: "preserveShortSequences",
        type: "bool"
      }
    ],
    outputs: [
      "ngrams",
      "ngrams_splits"
    ]
  },
  {
    tfOpName: "StringSplit",
    category: "string",
    inputs: [
      {
        start: 0,
        name: "input",
        type: "tensor"
      },
      {
        start: 1,
        name: "delimiter",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "skip_empty",
        name: "skipEmpty",
        type: "bool"
      }
    ],
    outputs: [
      "indices",
      "values",
      "shape"
    ]
  },
  {
    tfOpName: "StringToHashBucketFast",
    category: "string",
    inputs: [
      {
        start: 0,
        name: "input",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "num_buckets",
        name: "numBuckets",
        type: "number"
      }
    ]
  }
], dg = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: cg
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2023 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const hg = [
  {
    tfOpName: "Cast",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "SrcT",
        name: "sdtype",
        type: "dtype",
        notSupported: !0
      },
      {
        tfName: "DstT",
        name: "dtype",
        type: "dtype"
      }
    ]
  },
  {
    tfOpName: "ExpandDims",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "axis",
        type: "number"
      }
    ]
  },
  {
    tfOpName: "MirrorPad",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "padding",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "mode",
        name: "mode",
        type: "string"
      }
    ]
  },
  {
    tfOpName: "Pad",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "padding",
        type: "number[]"
      }
    ],
    attrs: [
      {
        tfName: "constant_value",
        name: "constantValue",
        type: "number",
        defaultValue: 0
      }
    ]
  },
  {
    tfOpName: "PadV2",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "padding",
        type: "number[]"
      },
      {
        start: 2,
        name: "constantValue",
        type: "number",
        defaultValue: 0
      }
    ]
  },
  {
    tfOpName: "Reshape",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "shape",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "Squeeze",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "axis",
        tfDeprecatedName: "squeeze_dims",
        name: "axis",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "SpaceToBatchND",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "blockShape",
        type: "number[]"
      },
      {
        start: 2,
        name: "paddings",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "BatchToSpaceND",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "blockShape",
        type: "number[]"
      },
      {
        start: 2,
        name: "crops",
        type: "number[]"
      }
    ]
  },
  {
    tfOpName: "DepthToSpace",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      }
    ],
    attrs: [
      {
        tfName: "block_size",
        name: "blockSize",
        type: "number"
      },
      {
        tfName: "data_format",
        name: "dataFormat",
        type: "string"
      }
    ]
  },
  {
    tfOpName: "BroadcastTo",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "x",
        type: "tensor"
      },
      {
        start: 1,
        name: "shape",
        type: "number[]"
      }
    ],
    attrs: []
  },
  {
    tfOpName: "BroadcastArgs",
    category: "transformation",
    inputs: [
      {
        start: 0,
        name: "s0",
        type: "tensor"
      },
      {
        start: 1,
        name: "s1",
        type: "tensor"
      }
    ],
    attrs: []
  }
], fg = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  json: hg
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
class Vt {
  // Loads the op mapping from the JSON file.
  constructor() {
    const e = [
      zy,
      Ly,
      Fy,
      Ry,
      By,
      Wy,
      Uy,
      Ky,
      Qy,
      Zy,
      My,
      tg,
      ag,
      ng,
      og,
      lg,
      mg,
      dg,
      fg
    ], t = [].concat(...e.map((a) => a.json));
    this.opMappers = t.reduce((a, r) => (a[r.tfOpName] = r, a), {});
  }
  // Singleton instance for the mapper
  static get Instance() {
    return this._instance || (this._instance = new this());
  }
  // Converts the model inference graph from Tensorflow GraphDef to local
  // representation for TensorFlow.js API
  transformGraph(e, t = {}) {
    const a = e.node, r = [], n = [], o = [], u = a.reduce((f, y) => (f[y.name] = this.mapNode(y), y.op.startsWith("Placeholder") ? r.push(f[y.name]) : y.op === "Const" ? n.push(f[y.name]) : (y.input == null || y.input.length === 0) && o.push(f[y.name]), f), {});
    let l = [];
    const p = [];
    let m = {}, c = {};
    t != null && (m = this.mapSignatureEntries(t.inputs), c = this.mapSignatureEntries(t.outputs));
    const d = Object.keys(u);
    d.forEach((f) => {
      const y = u[f];
      y.inputNames.forEach((T, _) => {
        const [w, , I] = B(T), E = u[w];
        if (E.outputs != null) {
          const D = E.outputs.indexOf(I);
          if (D !== -1) {
            const V = `${w}:${D}`;
            y.inputNames[_] = V;
          }
        }
        y.inputs.push(E), E.children.push(y);
      });
    }), Object.keys(c).length === 0 ? d.forEach((f) => {
      const y = u[f];
      y.children.length === 0 && p.push(y);
    }) : Object.keys(c).forEach((f) => {
      const [y] = B(f), T = u[y];
      T != null && (T.signatureKey = c[f], p.push(T));
    }), Object.keys(m).length > 0 ? Object.keys(m).forEach((f) => {
      const [y] = B(f), T = u[y];
      T && (T.signatureKey = m[f], l.push(T));
    }) : l = r;
    let h = {};
    e.library != null && e.library.function != null && (h = e.library.function.reduce((f, y) => (f[y.signature.name] = this.mapFunction(y), f), {}));
    const b = { nodes: u, inputs: l, outputs: p, weights: n, placeholders: r, signature: t, functions: h };
    return o.length > 0 && (b.initNodes = o), b;
  }
  mapSignatureEntries(e) {
    return Object.keys(e || {}).reduce((t, a) => (t[e[a].name] = a, t), {});
  }
  mapNode(e) {
    const t = Jn(e.op) || this.opMappers[e.op] || {};
    e.attr == null && (e.attr = {});
    const a = {
      name: e.name,
      op: e.op,
      category: t.category,
      inputNames: (e.input || []).map((r) => r.startsWith("^") ? r.slice(1) : r),
      inputs: [],
      children: [],
      inputParams: {},
      attrParams: {},
      rawAttrs: e.attr,
      outputs: t.outputs
    };
    return t.inputs != null && (a.inputParams = t.inputs.reduce((r, n) => (r[n.name] = {
      type: n.type,
      inputIndexStart: n.start,
      inputIndexEnd: n.end
    }, r), {})), t.attrs != null && (a.attrParams = t.attrs.reduce((r, n) => {
      const o = n.type;
      let u;
      switch (n.type) {
        case "string":
          u = Re(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = Re(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "string[]":
          u = Ge(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = Ge(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "number":
          u = Be(e.attr, n.tfName, n.defaultValue || 0), u === void 0 && n.tfDeprecatedName && (u = Be(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "number[]":
          u = Ue(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = Ue(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "bool":
          u = je(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = je(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "bool[]":
          u = Je(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = Je(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "shape":
          u = qe(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = qe(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "shape[]":
          u = Ke(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = Ke(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "dtype":
          u = He(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = He(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "dtype[]":
          u = We(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = We(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "func":
          u = Ft(e.attr, n.tfName, n.defaultValue), u === void 0 && n.tfDeprecatedName && (u = Ft(e.attr, n.tfDeprecatedName, n.defaultValue));
          break;
        case "tensor":
        case "tensors":
          break;
        default:
          throw new Error(`Unsupported param type: ${n.type} for op: ${e.op}`);
      }
      return r[n.name] = { value: u, type: o }, r;
    }, {})), a;
  }
  // map the TFunctionDef to TFJS graph object
  mapFunction(e) {
    const t = e.nodeDef, a = [], r = [];
    let n = {};
    t != null && (n = t.reduce((c, d) => (c[d.name] = this.mapNode(d), d.op === "Const" && r.push(c[d.name]), c), {}));
    const o = [], u = [];
    e.signature.inputArg.forEach((c) => {
      const [d] = B(c.name), h = {
        name: d,
        op: "Placeholder",
        inputs: [],
        inputNames: [],
        category: "graph",
        inputParams: {},
        attrParams: { dtype: { value: vt(c.type), type: "dtype" } },
        children: []
      };
      h.signatureKey = c.name, o.push(h), n[d] = h;
    }), Object.keys(n).forEach((c) => {
      const d = n[c];
      d.inputNames.forEach((h, b) => {
        const [f, , y] = B(h), T = n[f];
        if (T.outputs != null) {
          const _ = T.outputs.indexOf(y);
          if (_ !== -1) {
            const w = `${f}:${_}`;
            d.inputNames[b] = w;
          }
        }
        d.inputs.push(T), T.children.push(d);
      });
    });
    const p = e.ret;
    e.signature.outputArg.forEach((c) => {
      const [d, h] = B(p[c.name]), b = n[d];
      b != null && (b.defaultOutput = h, u.push(b));
    });
    const m = this.mapArgsToSignature(e);
    return { nodes: n, inputs: o, outputs: u, weights: r, placeholders: a, signature: m };
  }
  mapArgsToSignature(e) {
    return {
      methodName: e.signature.name,
      inputs: e.signature.inputArg.reduce((t, a) => (t[a.name] = this.mapArgToTensorInfo(a), t), {}),
      outputs: e.signature.outputArg.reduce((t, a) => (t[a.name] = this.mapArgToTensorInfo(a, e.ret), t), {})
    };
  }
  mapArgToTensorInfo(e, t) {
    let a = e.name;
    return t != null && (a = t[a]), { name: a, dtype: e.type };
  }
}
function yg(s) {
  const e = x().global;
  if (typeof e.atob < "u")
    return e.atob(s);
  if (typeof Buffer < "u")
    return new Buffer(s, "base64").toString();
  throw new Error("Unable to decode base64 in this environment. Missing built-in atob() or Buffer()");
}
function Qn(s, e) {
  const t = Array.isArray(s) ? String.fromCharCode.apply(null, s) : yg(s);
  return e ? t : t.toLowerCase();
}
function Re(s, e, t, a = !1) {
  const r = s[e];
  return r != null ? Qn(r.s, a) : t;
}
function je(s, e, t) {
  const a = s[e];
  return a ? a.b : t;
}
function Be(s, e, t) {
  const a = s[e] || {}, r = a.i != null ? a.i : a.f != null ? a.f : t;
  return typeof r == "number" ? r : parseInt(r, 10);
}
function vt(s) {
  switch (typeof s == "string" && (s = F[s]), s) {
    case F.DT_FLOAT:
    case F.DT_HALF:
      return "float32";
    case F.DT_INT32:
    case F.DT_INT64:
    case F.DT_INT8:
    case F.DT_UINT8:
      return "int32";
    case F.DT_BOOL:
      return "bool";
    case F.DT_DOUBLE:
      return "float32";
    case F.DT_STRING:
      return "string";
    default:
      return null;
  }
}
function Ft(s, e, t) {
  const a = s[e];
  return a && a.func ? a.func.name : t;
}
function He(s, e, t) {
  const a = s[e];
  return a && a.type ? vt(a.type) : t;
}
function We(s, e, t) {
  const a = s[e];
  return a && a.list && a.list.type ? a.list.type.map((r) => vt(r)) : t;
}
function Xn(s) {
  if (!s.unknownRank)
    return s.dim != null ? s.dim.map((e) => typeof e.size == "number" ? e.size : parseInt(e.size, 10)) : [];
}
function qe(s, e, t) {
  const a = s[e];
  return a && a.shape ? Xn(a.shape) : t;
}
function Ue(s, e, t) {
  const a = s[e];
  return a ? ((a.list.f && a.list.f.length ? a.list.f : a.list.i) || []).map((r) => typeof r == "number" ? r : parseInt(r, 10)) : t;
}
function Ge(s, e, t, a = !1) {
  const r = s[e];
  return r && r.list && r.list.s ? r.list.s.map((n) => Qn(n, a)) : t;
}
function Ke(s, e, t) {
  const a = s[e];
  return a && a.list && a.list.shape ? a.list.shape.map((r) => Xn(r)) : t;
}
function Je(s, e, t) {
  const a = s[e];
  return a && a.list && a.list.b ? a.list.b : t;
}
/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
class gg {
  constructor(e, t, a) {
    this.node = e, this.tensorMap = t, this.context = a, this.inputs = [], this.attrs = {}, this.inputs = e.inputNames.map((r) => this.getInput(r)), e.rawAttrs != null && (this.attrs = Object.keys(e.rawAttrs).reduce((r, n) => (r[n] = this.getAttr(n), r), {}));
  }
  /**
   * Return the value of the attribute or input param.
   * @param name String: name of attribute or input param.
   */
  getInput(e) {
    return k(e, this.tensorMap, this.context);
  }
  /**
   * Return the value of the attribute or input param.
   * @param name String: name of attribute or input param.
   */
  getAttr(e, t) {
    const a = this.node.rawAttrs[e];
    if (a.tensor != null)
      return k(e, this.tensorMap, this.context);
    if (a.i != null || a.f != null)
      return Be(this.node.rawAttrs, e, t);
    if (a.s != null)
      return Re(this.node.rawAttrs, e, t);
    if (a.b != null)
      return je(this.node.rawAttrs, e, t);
    if (a.shape != null)
      return qe(this.node.rawAttrs, e, t);
    if (a.type != null)
      return He(this.node.rawAttrs, e, t);
    if (a.list != null) {
      if (a.list.i != null || a.list.f != null)
        return Ue(this.node.rawAttrs, e, t);
      if (a.list.s != null)
        return Ge(this.node.rawAttrs, e, t);
      if (a.list.shape != null)
        return Ke(this.node.rawAttrs, e, t);
      if (a.list.b != null)
        return Je(this.node.rawAttrs, e, t);
      if (a.list.type != null)
        return We(this.node.rawAttrs, e, t);
    }
    return t;
  }
}
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const A = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  OP_SCOPE_SUFFIX: hs,
  abs: fs,
  acos: ys,
  acosh: gs,
  add: G,
  addN: Yr,
  all: bs,
  any: Ns,
  argMax: ws,
  argMin: Ts,
  asin: Ss,
  asinh: vs,
  atan: Os,
  atan2: _s,
  atanh: As,
  avgPool: Es,
  avgPool3d: ks,
  basicLSTMCell: Mr,
  batchNorm: Is,
  batchNorm2d: Ds,
  batchNorm3d: $s,
  batchNorm4d: Cs,
  batchToSpaceND: zs,
  bincount: xs,
  booleanMaskAsync: Cn,
  broadcastArgs: en,
  broadcastTo: Ls,
  buffer: Xe,
  cast: M,
  ceil: Vs,
  clipByValue: Fs,
  clone: pt,
  complex: Ps,
  concat: ce,
  concat1d: Rs,
  concat2d: js,
  concat3d: Bs,
  concat4d: Hs,
  conv1d: Ws,
  conv2d: qs,
  conv2dTranspose: Us,
  conv3d: Gs,
  conv3dTranspose: Ks,
  cos: Js,
  cosh: Qs,
  cosineWindow: Xs,
  cumprod: Zs,
  cumsum: Ys,
  denseBincount: Ms,
  depthToSpace: ea,
  depthwiseConv2d: rt,
  diag: tn,
  dilation2d: ta,
  div: st,
  divNoNan: sa,
  dot: aa,
  dropout: ra,
  einsum: sn,
  elu: na,
  enclosingPowerOfTwo: ia,
  equal: oa,
  erf: ua,
  euclideanNorm: la,
  exp: pa,
  expandDims: mt,
  expm1: ma,
  eye: ca,
  fft: da,
  fill: ha,
  floor: fa,
  floorDiv: ya,
  fused: Pn,
  gather: tt,
  gatherND: Vn,
  greater: ga,
  greaterEqual: ba,
  ifft: Na,
  imag: wa,
  image: ct,
  inTopKAsync: Fn,
  irfft: Ta,
  isFinite: Sa,
  isInf: va,
  isNaN: Oa,
  leakyRelu: _a,
  less: Aa,
  lessEqual: Ea,
  linalg: ka,
  linspace: an,
  localResponseNormalization: Ia,
  log: Da,
  log1p: $a,
  logSigmoid: Ca,
  logSoftmax: za,
  logSumExp: xa,
  logicalAnd: La,
  logicalNot: Va,
  logicalOr: Fa,
  logicalXor: Pa,
  losses: Ra,
  lowerBound: rn,
  matMul: W,
  max: ja,
  maxPool: Ba,
  maxPool3d: Ha,
  maxPoolWithArgmax: nn,
  maximum: Wa,
  mean: qa,
  meshgrid: on,
  min: Ua,
  minimum: Ga,
  mirrorPad: Ka,
  mod: Ja,
  moments: Qa,
  movingAverage: zn,
  mul: Y,
  multiRNNCell: un,
  multinomial: ln,
  neg: Xa,
  norm: Za,
  notEqual: Ya,
  oneHot: Se,
  ones: Z,
  onesLike: Ma,
  op: S,
  outerProduct: pn,
  pad: te,
  pad1d: mn,
  pad2d: cn,
  pad3d: dn,
  pad4d: hn,
  pool: er,
  pow: at,
  prelu: tr,
  print: sr,
  prod: ar,
  raggedGather: fn,
  raggedRange: yn,
  raggedTensorToTensor: gn,
  rand: bn,
  randomGamma: Tn,
  randomNormal: Ze,
  randomStandardNormal: Sn,
  randomUniform: rr,
  range: nr,
  real: ir,
  reciprocal: or,
  relu: ur,
  relu6: lr,
  reshape: v,
  reverse: se,
  reverse1d: vn,
  reverse2d: On,
  reverse3d: _n,
  reverse4d: An,
  rfft: pr,
  round: mr,
  rsqrt: cr,
  scalar: R,
  scatterND: xn,
  searchSorted: De,
  selu: dr,
  separableConv2d: hr,
  setdiff1dAsync: En,
  sigmoid: ie,
  sign: fr,
  signal: yr,
  sin: gr,
  sinh: br,
  slice: q,
  slice1d: Nr,
  slice2d: wr,
  slice3d: Tr,
  slice4d: Sr,
  softmax: vr,
  softplus: Or,
  spaceToBatchND: _r,
  sparse: Ar,
  sparseToDense: Ln,
  spectral: Er,
  split: kr,
  sqrt: Ir,
  square: Dr,
  squaredDifference: $r,
  squeeze: et,
  stack: ee,
  step: Cr,
  stridedSlice: zr,
  string: xr,
  sub: oe,
  sum: Lr,
  tan: Vr,
  tanh: Te,
  tensor: U,
  tensor1d: dt,
  tensor2d: ve,
  tensor3d: Fr,
  tensor4d: kn,
  tensor5d: In,
  tensor6d: Dn,
  tile: Pr,
  topk: Rr,
  transpose: nt,
  truncatedNormal: jr,
  unique: Br,
  unsortedSegmentSum: Hr,
  unstack: ae,
  upperBound: $n,
  variable: Wr,
  where: qr,
  whereAsync: gt,
  zeros: Ur,
  zerosLike: Gr
}, Symbol.toStringTag, { value: "Module" }));
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const bg = (s, e, t, a = A) => {
  switch (s.op) {
    case "BiasAdd":
    case "AddV2":
    case "Add":
      return [a.add(i("a", s, e, t), i("b", s, e, t))];
    case "AddN":
      return [a.addN(i("tensors", s, e, t))];
    case "FloorMod":
    case "Mod":
      return [a.mod(i("a", s, e, t), i("b", s, e, t))];
    case "Mul":
      return [a.mul(i("a", s, e, t), i("b", s, e, t))];
    case "RealDiv":
    case "Div":
      return [a.div(i("a", s, e, t), i("b", s, e, t))];
    case "DivNoNan":
      return [a.divNoNan(i("a", s, e, t), i("b", s, e, t))];
    case "FloorDiv":
      return [a.floorDiv(i("a", s, e, t), i("b", s, e, t))];
    case "Sub":
      return [a.sub(i("a", s, e, t), i("b", s, e, t))];
    case "Minimum":
      return [a.minimum(i("a", s, e, t), i("b", s, e, t))];
    case "Maximum":
      return [a.maximum(i("a", s, e, t), i("b", s, e, t))];
    case "Pow":
      return [a.pow(i("a", s, e, t), i("b", s, e, t))];
    case "SquaredDifference":
      return [a.squaredDifference(i("a", s, e, t), i("b", s, e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Ng = (s, e, t, a = A) => {
  switch (s.op) {
    case "Abs":
    case "ComplexAbs":
      return [a.abs(i("x", s, e, t))];
    case "Acos":
      return [a.acos(i("x", s, e, t))];
    case "Acosh":
      return [a.acosh(i("x", s, e, t))];
    case "Asin":
      return [a.asin(i("x", s, e, t))];
    case "Asinh":
      return [a.asinh(i("x", s, e, t))];
    case "Atan":
      return [a.atan(i("x", s, e, t))];
    case "Atan2":
      return [a.atan2(i("x", s, e, t), i("y", s, e, t))];
    case "Atanh":
      return [a.atanh(i("x", s, e, t))];
    case "Ceil":
      return [a.ceil(i("x", s, e, t))];
    case "Complex":
      return [a.complex(i("real", s, e, t), i("imag", s, e, t))];
    case "Cos":
      return [a.cos(i("x", s, e, t))];
    case "Cosh":
      return [a.cosh(i("x", s, e, t))];
    case "Elu":
      return [a.elu(i("x", s, e, t))];
    case "Erf":
      return [a.erf(i("x", s, e, t))];
    case "Exp":
      return [a.exp(i("x", s, e, t))];
    case "Expm1":
      return [a.expm1(i("x", s, e, t))];
    case "Floor":
      return [a.floor(i("x", s, e, t))];
    case "Log":
      return [a.log(i("x", s, e, t))];
    case "Log1p":
      return [a.log1p(i("x", s, e, t))];
    case "Imag":
      return [a.imag(i("x", s, e, t))];
    case "Neg":
      return [a.neg(i("x", s, e, t))];
    case "Reciprocal":
      return [a.reciprocal(i("x", s, e, t))];
    case "Real":
      return [a.real(i("x", s, e, t))];
    case "Relu":
      return [a.relu(i("x", s, e, t))];
    case "Round":
      return [a.round(i("x", s, e, t))];
    case "Selu":
      return [a.selu(i("x", s, e, t))];
    case "Sigmoid":
      return [a.sigmoid(i("x", s, e, t))];
    case "Sin":
      return [a.sin(i("x", s, e, t))];
    case "Sign":
      return [a.sign(i("x", s, e, t))];
    case "Sinh":
      return [a.sinh(i("x", s, e, t))];
    case "Softplus":
      return [a.softplus(i("x", s, e, t))];
    case "Sqrt":
      return [a.sqrt(i("x", s, e, t))];
    case "Square":
      return [a.square(i("x", s, e, t))];
    case "Tanh":
      return [a.tanh(i("x", s, e, t))];
    case "Tan":
      return [a.tan(i("x", s, e, t))];
    case "ClipByValue":
      return [a.clipByValue(i("x", s, e, t), i("clipValueMin", s, e, t), i("clipValueMax", s, e, t))];
    case "Relu6":
      return [a.relu6(i("x", s, e, t))];
    case "Rsqrt":
      return [a.rsqrt(k(s.inputNames[0], e, t))];
    case "Prod":
      return [a.prod(i("x", s, e, t), i("axes", s, e, t))];
    case "LeakyRelu":
      return [a.leakyRelu(i("x", s, e, t), i("alpha", s, e, t))];
    case "Prelu":
      return [a.prelu(i("x", s, e, t), i("alpha", s, e, t))];
    case "IsNan":
      return [a.isNaN(k(s.inputNames[0], e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function C(s, e, t = "") {
  if (!(typeof s == "number" || typeof e == "number")) {
    N(s.length === e.length, () => t + ` Shapes ${s} and ${e} must match`);
    for (let a = 0; a < s.length; a++) {
      const r = s[a], n = e[a];
      N(r < 0 || n < 0 || r === n, () => t + ` Shapes ${s} and ${e} must match`);
    }
  }
}
function Pt(s) {
  return !(typeof s == "number" || s.some((e) => e < 0));
}
function re(s, e, t) {
  let a = Qe(s, t);
  const r = !Pt(a);
  if (r && e.length === 0)
    throw new Error(`Tried to calculate elements of an empty list with non-fully-defined elementShape: ${a}`);
  if (r && e.forEach((n) => {
    a = Qe(n.shape, a);
  }), !Pt(a))
    throw new Error(`Non-fully-defined elementShape: ${a}`);
  return a;
}
function Qe(s, e) {
  if (typeof s == "number")
    return e;
  if (typeof e == "number")
    return s;
  if (s.length !== e.length)
    throw new Error(`Incompatible ranks during merge: ${s} vs. ${e}`);
  const t = [];
  for (let a = 0; a < s.length; ++a) {
    const r = s[a], n = e[a];
    if (r >= 0 && n >= 0 && r !== n)
      throw new Error(`Incompatible shape during merge: ${s} vs. ${e}`);
    t[a] = r >= 0 ? r : n;
  }
  return t;
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
class wg {
  constructor(e, t, a, r, n, o, u) {
    this.name = e, this.dtype = t, this.maxSize = a, this.elementShape = r, this.identicalElementShapes = n, this.dynamicSize = o, this.clearAfterRead = u, this.tensors = [], this.closed_ = !1, this.idTensor = R(0), P(this.idTensor);
  }
  get id() {
    return this.idTensor.id;
  }
  get closed() {
    return this.closed_;
  }
  /**
   * Dispose the tensors and idTensor and mark the TensoryArray as closed.
   */
  clearAndClose(e) {
    this.tensors.forEach((t) => {
      (e == null || !e.has(t.tensor.id)) && t.tensor.dispose();
    }), this.tensors = [], this.closed_ = !0, this.idTensor.dispose();
  }
  size() {
    return this.tensors.length;
  }
  /**
   * Read the value at location index in the TensorArray.
   * @param index Number the index to read from.
   */
  read(e) {
    if (this.closed_)
      throw new Error(`TensorArray ${this.name} has already been closed.`);
    if (e < 0 || e >= this.size())
      throw new Error(`Tried to read from index ${e}, but array size is: ${this.size()}`);
    const t = this.tensors[e];
    if (t.cleared)
      throw new Error(`TensorArray ${this.name}: Could not read index ${e} twice because it was cleared after a previous read (perhaps try setting clear_after_read = false?).`);
    return this.clearAfterRead && (t.cleared = !0), t.read = !0, t.tensor;
  }
  /**
   * Helper method to read multiple tensors from the specified indices.
   */
  readMany(e) {
    return e.map((t) => this.read(t));
  }
  /**
   * Write value into the index of the TensorArray.
   * @param index number the index to write to.
   * @param tensor
   */
  write(e, t) {
    if (this.closed_)
      throw new Error(`TensorArray ${this.name} has already been closed.`);
    if (e < 0 || !this.dynamicSize && e >= this.maxSize)
      throw new Error(`Tried to write to index ${e}, but array is not resizeable and size is: ${this.maxSize}`);
    const a = this.tensors[e] || {};
    if (t.dtype !== this.dtype)
      throw new Error(`TensorArray ${this.name}: Could not write to TensorArray index ${e},
          because the value dtype is ${t.dtype}, but TensorArray dtype is ${this.dtype}.`);
    if (this.size() === 0 && (this.elementShape == null || this.elementShape.length === 0) && (this.elementShape = t.shape), C(this.elementShape, t.shape, `TensorArray ${this.name}: Could not write to TensorArray index ${e}.`), a.read)
      throw new Error(`TensorArray ${this.name}: Could not write to TensorArray index ${e}, because it has already been read.`);
    if (a.written)
      throw new Error(`TensorArray ${this.name}: Could not write to TensorArray index ${e}, because it has already been written.`);
    a.tensor = t, P(t), a.written = !0, this.tensors[e] = a;
  }
  /**
   * Helper method to write multiple tensors to the specified indices.
   */
  writeMany(e, t) {
    if (e.length !== t.length)
      throw new Error(`TensorArray ${this.name}: could not write multiple tensors,because the index size: ${e.length} is not the same as tensors size: ${t.length}.`);
    e.forEach((a, r) => this.write(a, t[r]));
  }
  /**
   * Return selected values in the TensorArray as a packed Tensor. All of
   * selected values must have been written and their shapes must all match.
   * @param [indices] number[] Optional. Taking values in [0, max_value). If the
   *    TensorArray is not dynamic, max_value=size(). If not specified returns
   *    all tensors in the original order.
   * @param [dtype]
   */
  gather(e, t) {
    if (t && t !== this.dtype)
      throw new Error(`TensorArray dtype is ${this.dtype} but gather requested dtype ${t}`);
    if (e)
      e = e.slice(0, this.size());
    else {
      e = [];
      for (let r = 0; r < this.size(); r++)
        e.push(r);
    }
    if (e.length === 0)
      return U([], [0].concat(this.elementShape));
    const a = this.readMany(e);
    return C(this.elementShape, a[0].shape, "TensorArray shape mismatch: "), ee(a, 0);
  }
  /**
   * Return the values in the TensorArray as a concatenated Tensor.
   */
  concat(e) {
    if (e && e !== this.dtype)
      throw new Error(`TensorArray dtype is ${this.dtype} but concat requested dtype ${e}`);
    if (this.size() === 0)
      return U([], [0].concat(this.elementShape));
    const t = [];
    for (let r = 0; r < this.size(); r++)
      t.push(r);
    const a = this.readMany(t);
    return C(this.elementShape, a[0].shape, `TensorArray shape mismatch: tensor array shape (${this.elementShape}) vs first tensor shape (${a[0].shape})`), ce(a, 0);
  }
  /**
   * Scatter the values of a Tensor in specific indices of a TensorArray.
   * @param indices nummber[] values in [0, max_value). If the
   *    TensorArray is not dynamic, max_value=size().
   * @param tensor Tensor input tensor.
   */
  scatter(e, t) {
    if (t.dtype !== this.dtype)
      throw new Error(`TensorArray dtype is ${this.dtype} but tensor has dtype ${t.dtype}`);
    if (e.length !== t.shape[0])
      throw new Error(`Expected len(indices) == tensor.shape[0], but saw: ${e.length} vs. ${t.shape[0]}`);
    const a = Math.max(...e);
    if (!this.dynamicSize && a >= this.maxSize)
      throw new Error(`Max index must be < array size (${a}  vs. ${this.maxSize})`);
    this.writeMany(e, ae(t, 0));
  }
  /**
   * Split the values of a Tensor into the TensorArray.
   * @param length number[] with the lengths to use when splitting value along
   *    its first dimension.
   * @param tensor Tensor, the tensor to split.
   */
  split(e, t) {
    if (t.dtype !== this.dtype)
      throw new Error(`TensorArray dtype is ${this.dtype} but tensor has dtype ${t.dtype}`);
    let a = 0;
    const r = e.map((l) => (a += l, a));
    if (a !== t.shape[0])
      throw new Error(`Expected sum of lengths to be equal to
          tensor.shape[0], but sum of lengths is
        ${a}, and tensor's shape is: ${t.shape}`);
    if (!this.dynamicSize && e.length !== this.maxSize)
      throw new Error(`TensorArray's size is not equal to the size of lengths (${this.maxSize} vs. ${e.length}), and the TensorArray is not marked as dynamically resizeable`);
    const n = a === 0 ? 0 : t.size / a, o = [];
    z(() => {
      t = v(t, [1, a, n]);
      for (let l = 0; l < e.length; ++l) {
        const m = [0, l === 0 ? 0 : r[l - 1], 0], c = [1, e[l], n];
        o[l] = v(q(t, m, c), this.elementShape);
      }
      return o;
    });
    const u = [];
    for (let l = 0; l < e.length; l++)
      u[l] = l;
    this.writeMany(u, o);
  }
}
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
class J {
  /**
   *
   * @param tensors list of tensors
   * @param elementShape shape of each tensor, this can be a single number (any
   * shape is allowed) or partial shape (dim = -1).
   * @param elementDtype data type of each tensor
   * @param maxNumElements The maximum allowed size of `tensors`. Defaults to -1
   *   meaning that the size of `tensors` is unbounded.
   */
  constructor(e, t, a, r = -1) {
    this.tensors = e, this.elementShape = t, this.elementDtype = a, e != null && e.forEach((n) => {
      if (a !== n.dtype)
        throw new Error(`Invalid data types; op elements ${a}, but list elements ${n.dtype}`);
      C(t, n.shape, "TensorList shape mismatch: "), P(n);
    }), this.idTensor = R(0), this.maxNumElements = r, P(this.idTensor);
  }
  get id() {
    return this.idTensor.id;
  }
  /**
   * Get a new TensorList containing a copy of the underlying tensor container.
   */
  copy() {
    return new J([...this.tensors], this.elementShape, this.elementDtype);
  }
  /**
   * Dispose the tensors and idTensor and clear the tensor list.
   */
  clearAndClose(e) {
    this.tensors.forEach((t) => {
      (e == null || !e.has(t.id)) && t.dispose();
    }), this.tensors.length = 0, this.idTensor.dispose();
  }
  /**
   * The size of the tensors in the tensor list.
   */
  size() {
    return this.tensors.length;
  }
  /**
   * Return a tensor that stacks a list of rank-R tf.Tensors into one rank-(R+1)
   * tf.Tensor.
   * @param elementShape shape of each tensor
   * @param elementDtype data type of each tensor
   * @param numElements the number of elements to stack
   */
  stack(e, t, a = -1) {
    if (t !== this.elementDtype)
      throw new Error(`Invalid data types; op elements ${t}, but list elements ${this.elementDtype}`);
    if (a !== -1 && this.tensors.length !== a)
      throw new Error(`Operation expected a list with ${a} elements but got a list with ${this.tensors.length} elements.`);
    C(e, this.elementShape, "TensorList shape mismatch: ");
    const r = re(this.elementShape, this.tensors, e);
    return z(() => {
      const n = this.tensors.map((o) => v(o, r));
      return ee(n, 0);
    });
  }
  /**
   * Pop a tensor from the end of the list.
   * @param elementShape shape of the tensor
   * @param elementDtype data type of the tensor
   */
  popBack(e, t) {
    if (t !== this.elementDtype)
      throw new Error(`Invalid data types; op elements ${t}, but list elements ${this.elementDtype}`);
    if (this.size() === 0)
      throw new Error("Trying to pop from an empty list.");
    const a = re(this.elementShape, this.tensors, e), r = this.tensors.pop();
    return r.kept = !1, C(r.shape, e, "TensorList shape mismatch: "), v(r, a);
  }
  /**
   * Push a tensor to the end of the list.
   * @param tensor Tensor to be pushed.
   */
  pushBack(e) {
    if (e.dtype !== this.elementDtype)
      throw new Error(`Invalid data types; op elements ${e.dtype}, but list elements ${this.elementDtype}`);
    if (C(e.shape, this.elementShape, "TensorList shape mismatch: "), this.maxNumElements === this.size())
      throw new Error("Trying to push element into a full list.");
    P(e), this.tensors.push(e);
  }
  /**
   * Update the size of the list.
   * @param size the new size of the list.
   */
  resize(e) {
    if (e < 0)
      throw new Error(`TensorListResize expects size to be non-negative. Got: ${e}`);
    if (this.maxNumElements !== -1 && e > this.maxNumElements)
      throw new Error(`TensorListResize input size ${e} is greater maxNumElement ${this.maxNumElements}.`);
    const t = new J([], this.elementShape, this.elementDtype, this.maxNumElements);
    t.tensors.length = e;
    for (let a = 0; a < Math.min(this.tensors.length, e); ++a)
      t.tensors[a] = this.tensors[a];
    return t;
  }
  /**
   * Retrieve the element at the provided index
   * @param elementShape shape of the tensor
   * @param elementDtype dtype of the tensor
   * @param elementIndex index of the tensor
   */
  getItem(e, t, a) {
    if (a !== this.elementDtype)
      throw new Error(`Invalid data types; op elements ${a}, but list elements ${this.elementDtype}`);
    if (e < 0 || e > this.tensors.length)
      throw new Error(`Trying to access element ${e} in a list with ${this.tensors.length} elements.`);
    if (this.tensors[e] == null)
      throw new Error(`element at index ${e} is null.`);
    C(this.tensors[e].shape, t, "TensorList shape mismatch: ");
    const r = re(this.elementShape, this.tensors, t);
    return v(this.tensors[e], r);
  }
  /**
   * Set the tensor at the index
   * @param elementIndex index of the tensor
   * @param tensor the tensor to be inserted into the list
   */
  setItem(e, t) {
    if (t.dtype !== this.elementDtype)
      throw new Error(`Invalid data types; op elements ${t.dtype}, but list elements ${this.elementDtype}`);
    if (e < 0 || this.maxNumElements !== -1 && e >= this.maxNumElements)
      throw new Error(`Trying to set element ${e} in a list with max ${this.maxNumElements} elements.`);
    C(this.elementShape, t.shape, "TensorList shape mismatch: "), P(t), this.tensors[e] != null && (this.tensors[e].kept = !1), this.tensors[e] = t;
  }
  /**
   * Return selected values in the TensorList as a stacked Tensor. All of
   * selected values must have been written and their shapes must all match.
   * @param indices indices of tensors to gather
   * @param elementDtype output tensor dtype
   * @param elementShape output tensor element shape
   */
  gather(e, t, a) {
    if (t !== this.elementDtype)
      throw new Error(`Invalid data types; op elements ${t}, but list elements ${this.elementDtype}`);
    C(this.elementShape, a, "TensorList shape mismatch: "), e = e.slice(0, this.size());
    const r = re(this.elementShape, this.tensors, a);
    return e.length === 0 ? U([], [0].concat(r)) : z(() => {
      const n = e.map((o) => v(this.tensors[o], r));
      return ee(n, 0);
    });
  }
  /**
   * Return the values in the TensorList as a concatenated Tensor.
   * @param elementDtype output tensor dtype
   * @param elementShape output tensor element shape
   */
  concat(e, t) {
    if (e && e !== this.elementDtype)
      throw new Error(`TensorList dtype is ${this.elementDtype} but concat requested dtype ${e}`);
    C(this.elementShape, t, "TensorList shape mismatch: ");
    const a = re(this.elementShape, this.tensors, t);
    return this.size() === 0 ? U([], [0].concat(a)) : z(() => {
      const r = this.tensors.map((n) => v(n, a));
      return ce(r, 0);
    });
  }
}
function Tg(s, e, t) {
  const a = s.dtype;
  if (s.shape.length < 1)
    throw new Error(`Tensor must be at least a vector, but saw shape: ${s.shape}`);
  if (s.dtype !== t)
    throw new Error(`Invalid data types; op elements ${s.dtype}, but list elements ${t}`);
  const r = s.shape.slice(1);
  C(r, e, "TensorList shape mismatch: ");
  const n = ae(s);
  return new J(n, e, a);
}
function Sg(s, e, t, a) {
  return new J([], s, e, a);
}
function vg(s, e, t, a) {
  if (e.length !== s.shape[0])
    throw new Error(`Expected len(indices) == tensor.shape[0], but saw: ${e.length} vs. ${s.shape[0]}`);
  const r = Math.max(...e);
  if (a != null && a !== -1 && r >= a)
    throw new Error(`Max index must be < array size (${r}  vs. ${a})`);
  const n = new J([], t, s.dtype, a), o = ae(s, 0);
  return e.forEach((u, l) => {
    n.setItem(u, o[l]);
  }), n;
}
function Og(s, e, t) {
  let a = 0;
  const r = e.map((m) => (a += m, a));
  if (a !== s.shape[0])
    throw new Error(`Expected sum of lengths to be equal to
          tensor.shape[0], but sum of lengths is
        ${a}, and tensor's shape is: ${s.shape}`);
  const n = s.shape.slice(1), o = Qe(n, t), u = a === 0 ? 0 : s.size / a, l = z(() => {
    const m = [];
    s = v(s, [1, a, u]);
    for (let c = 0; c < e.length; ++c) {
      const h = [0, c === 0 ? 0 : r[c - 1], 0], b = [1, e[c], u];
      m[c] = v(q(s, h, b), o);
    }
    return s.dispose(), m;
  }), p = new J([], t, s.dtype, e.length);
  for (let m = 0; m < l.length; m++)
    p.setItem(m, l[m]);
  return p;
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const _g = async (s, e, t) => {
  switch (s.op) {
    case "If":
    case "StatelessIf": {
      const a = i("thenBranch", s, e, t), r = i("elseBranch", s, e, t), n = i("cond", s, e, t), o = i("args", s, e, t);
      return (await n.data())[0] ? t.functionMap[a].executeFunctionAsync(o, t.tensorArrayMap, t.tensorListMap) : t.functionMap[r].executeFunctionAsync(o, t.tensorArrayMap, t.tensorListMap);
    }
    case "While":
    case "StatelessWhile": {
      const a = i("body", s, e, t), r = i("cond", s, e, t), n = i("args", s, e, t), o = await t.functionMap[r].executeFunctionAsync(n, t.tensorArrayMap, t.tensorListMap), u = n.map((m) => m.id);
      let l = await o[0].data();
      o.forEach((m) => {
        !m.kept && u.indexOf(m.id) === -1 && m.dispose();
      });
      let p = n;
      for (; l[0]; ) {
        const m = p;
        p = await t.functionMap[a].executeFunctionAsync(p, t.tensorArrayMap, t.tensorListMap);
        const c = p.map((h) => h.id);
        m.forEach((h) => {
          !h.kept && u.indexOf(h.id) === -1 && c.indexOf(h.id) === -1 && h.dispose();
        });
        const d = await t.functionMap[r].executeFunctionAsync(p, t.tensorArrayMap, t.tensorListMap);
        l = await d[0].data(), d.forEach((h) => {
          !h.kept && u.indexOf(h.id) === -1 && c.indexOf(h.id) === -1 && h.dispose();
        });
      }
      return p;
    }
    case "LoopCond": {
      const a = i("pred", s, e, t);
      return [H(a)];
    }
    case "Switch": {
      const a = i("pred", s, e, t);
      let r = i("data", s, e, t);
      return r.kept || (r = H(r)), (await a.data())[0] ? [void 0, r] : [r, void 0];
    }
    case "Merge": {
      const a = s.inputNames.find((r) => k(r, e, t) !== void 0);
      if (a) {
        const r = k(a, e, t);
        return [H(r)];
      }
      return;
    }
    case "Enter": {
      const a = i("frameName", s, e, t), r = i("tensor", s, e, t);
      return t.enterFrame(a), [H(r)];
    }
    case "Exit": {
      const a = i("tensor", s, e, t);
      return t.exitFrame(), [H(a)];
    }
    case "NextIteration": {
      const a = i("tensor", s, e, t);
      return t.nextIteration(), [H(a)];
    }
    case "TensorArrayV3": {
      const a = i("size", s, e, t), r = i("dtype", s, e, t), n = i("elementShape", s, e, t), o = i("dynamicSize", s, e, t), u = i("clearAfterRead", s, e, t), l = i("identicalElementShapes", s, e, t), p = i("name", s, e, t), m = new wg(p, r, a, n, l, o, u);
      return t.addTensorArray(m), [m.idTensor, R(1)];
    }
    case "TensorArrayWriteV3": {
      const a = i("tensorArrayId", s, e, t), r = i("index", s, e, t), n = i("tensor", s, e, t), o = t.getTensorArray(a.id);
      return o.write(r, n), [o.idTensor];
    }
    case "TensorArrayReadV3": {
      const a = i("tensorArrayId", s, e, t), r = i("index", s, e, t);
      return [t.getTensorArray(a.id).read(r)];
    }
    case "TensorArrayGatherV3": {
      const a = i("tensorArrayId", s, e, t), r = i("indices", s, e, t), n = i("dtype", s, e, t);
      return [t.getTensorArray(a.id).gather(r, n)];
    }
    case "TensorArrayScatterV3": {
      const a = i("tensorArrayId", s, e, t), r = i("indices", s, e, t), n = i("tensor", s, e, t), o = t.getTensorArray(a.id);
      return o.scatter(r, n), [o.idTensor];
    }
    case "TensorArrayConcatV3": {
      const a = i("tensorArrayId", s, e, t), r = t.getTensorArray(a.id), n = i("dtype", s, e, t);
      return [r.concat(n)];
    }
    case "TensorArraySplitV3": {
      const a = i("tensorArrayId", s, e, t), r = i("tensor", s, e, t), n = i("lengths", s, e, t), o = t.getTensorArray(a.id);
      return o.split(n, r), [o.idTensor];
    }
    case "TensorArraySizeV3": {
      const a = i("tensorArrayId", s, e, t), r = t.getTensorArray(a.id);
      return [R(r.size(), "int32")];
    }
    case "TensorArrayCloseV3": {
      const a = i("tensorArrayId", s, e, t), r = t.getTensorArray(a.id);
      return r.clearAndClose(), [r.idTensor];
    }
    case "TensorListSetItem": {
      const a = i("tensorListId", s, e, t), r = i("index", s, e, t), n = i("tensor", s, e, t), o = t.getTensorList(a.id);
      return o.setItem(r, n), [o.idTensor];
    }
    case "TensorListGetItem": {
      const a = i("tensorListId", s, e, t), r = i("index", s, e, t), n = i("elementShape", s, e, t), o = i("elementDType", s, e, t);
      return [t.getTensorList(a.id).getItem(r, n, o)];
    }
    case "TensorListScatterV2":
    case "TensorListScatter": {
      const a = i("indices", s, e, t), r = i("tensor", s, e, t), n = i("elementShape", s, e, t), o = i("numElements", s, e, t), u = vg(r, a, n, o);
      return t.addTensorList(u), [u.idTensor];
    }
    case "TensorListReserve":
    case "EmptyTensorList": {
      const a = i("elementShape", s, e, t), r = i("elementDType", s, e, t);
      let n;
      s.op === "TensorListReserve" ? n = "numElements" : n = "maxNumElements";
      const o = i(n, s, e, t), u = s.op === "TensorListReserve" ? -1 : o, l = Sg(a, r, o, u);
      return t.addTensorList(l), [l.idTensor];
    }
    case "TensorListGather": {
      const a = i("tensorListId", s, e, t), r = i("indices", s, e, t), n = i("elementShape", s, e, t), o = i("elementDType", s, e, t);
      return [t.getTensorList(a.id).gather(r, o, n)];
    }
    case "TensorListStack": {
      const a = i("tensorListId", s, e, t), r = i("elementShape", s, e, t), n = i("elementDType", s, e, t), o = i("numElements", s, e, t);
      return [t.getTensorList(a.id).stack(r, n, o)];
    }
    case "TensorListFromTensor": {
      const a = i("tensor", s, e, t), r = i("elementShape", s, e, t), n = i("elementDType", s, e, t), o = Tg(a, r, n);
      return t.addTensorList(o), [o.idTensor];
    }
    case "TensorListConcat":
    case "TensorListConcatV2": {
      const a = i("tensorListId", s, e, t), r = t.getTensorList(a.id), n = i("dtype", s, e, t), o = i("elementShape", s, e, t);
      return [r.concat(n, o)];
    }
    case "TensorListPushBack": {
      const a = i("tensorListId", s, e, t), r = i("tensor", s, e, t), n = t.getTensorList(a.id);
      return n.pushBack(r), [n.idTensor];
    }
    case "TensorListPopBack": {
      const a = i("tensorListId", s, e, t), r = i("elementShape", s, e, t), n = i("elementDType", s, e, t);
      return [t.getTensorList(a.id).popBack(r, n)];
    }
    case "TensorListSplit": {
      const a = i("tensor", s, e, t), r = i("elementShape", s, e, t), n = i("lengths", s, e, t), o = Og(a, n, r);
      return t.addTensorList(o), [o.idTensor];
    }
    case "TensorListLength": {
      const a = i("tensorListId", s, e, t), r = t.getTensorList(a.id);
      return [R(r.size(), "int32")];
    }
    case "TensorListResize": {
      const a = i("tensorListId", s, e, t), r = i("size", s, e, t), o = t.getTensorList(a.id).resize(r);
      return t.addTensorList(o), [o.idTensor];
    }
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Rt(s, e, t) {
  const [a, r] = i("fusedOps", s, e, t), n = a === "biasadd", o = !n, u = r === "prelu", l = a === "fusedbatchnorm", p = i("numArgs", s, e, t);
  if (n) {
    if (u && p !== 2)
      throw new Error("FusedConv2d and DepthwiseConv2d with BiasAdd and Prelu must have two extra arguments: bias and alpha.");
    if (!u && n && p !== 1)
      throw new Error("FusedConv2d and DepthwiseConv2d with BiasAdd must have one extra argument: bias.");
  }
  if (l)
    throw new Error("FusedConv2d and DepthwiseConv2d with FusedBatchNorm is not supported");
  const m = i("strides", s, e, t), c = be(s, e, t), d = i("dataFormat", s, e, t).toUpperCase(), h = i("dilations", s, e, t);
  let [b, f] = i("args", s, e, t);
  o && (f = b, b = void 0);
  const y = i("leakyreluAlpha", s, e, t);
  return {
    stride: m,
    pad: c,
    dataFormat: d,
    dilations: h,
    biasArg: b,
    preluArg: f,
    activationFunc: r,
    leakyreluAlpha: y
  };
}
const Ag = (s, e, t, a = A) => {
  switch (s.op) {
    case "Conv1D": {
      const r = i("stride", s, e, t), n = i("pad", s, e, t), o = i("dataFormat", s, e, t).toUpperCase(), u = i("dilation", s, e, t);
      return [a.conv1d(i("x", s, e, t), i("filter", s, e, t), r, n, o, u)];
    }
    case "Conv2D": {
      const r = i("strides", s, e, t), n = be(s, e, t), o = i("dataFormat", s, e, t).toUpperCase(), u = i("dilations", s, e, t);
      return [a.conv2d(i("x", s, e, t), i("filter", s, e, t), [r[1], r[2]], n, o, [u[1], u[2]])];
    }
    case "_FusedConv2D": {
      const { stride: r, pad: n, dataFormat: o, dilations: u, biasArg: l, preluArg: p, activationFunc: m, leakyreluAlpha: c } = Rt(s, e, t);
      return [a.fused.conv2d({
        x: i("x", s, e, t),
        filter: i("filter", s, e, t),
        strides: [r[1], r[2]],
        pad: n,
        dataFormat: o,
        dilations: [u[1], u[2]],
        bias: l,
        activation: m,
        preluActivationWeights: p,
        leakyreluAlpha: c
      })];
    }
    case "FusedDepthwiseConv2dNative": {
      const { stride: r, pad: n, dataFormat: o, dilations: u, biasArg: l, preluArg: p, activationFunc: m, leakyreluAlpha: c } = Rt(s, e, t);
      return [a.fused.depthwiseConv2d({
        x: i("x", s, e, t),
        filter: i("filter", s, e, t),
        strides: [r[1], r[2]],
        pad: n,
        dataFormat: o,
        dilations: [u[1], u[2]],
        bias: l,
        activation: m,
        preluActivationWeights: p,
        leakyreluAlpha: c
      })];
    }
    case "Conv2DBackpropInput":
    case "Conv2dTranspose": {
      const r = i("outputShape", s, e, t), n = i("strides", s, e, t), o = be(s, e, t);
      return [a.conv2dTranspose(i("x", s, e, t), i("filter", s, e, t), r, [n[1], n[2]], o)];
    }
    case "DepthwiseConv2dNative":
    case "DepthwiseConv2d": {
      const r = i("strides", s, e, t), n = be(s, e, t), o = i("dilations", s, e, t), u = i("dataFormat", s, e, t).toUpperCase();
      return [a.depthwiseConv2d(i("input", s, e, t), i("filter", s, e, t), [r[1], r[2]], n, u, [o[1], o[2]])];
    }
    case "Conv3D": {
      const r = i("strides", s, e, t), n = i("pad", s, e, t), o = i("dataFormat", s, e, t).toUpperCase(), u = i("dilations", s, e, t);
      return [a.conv3d(i("x", s, e, t), i("filter", s, e, t), [r[1], r[2], r[3]], n, o, [u[1], u[2], u[3]])];
    }
    case "AvgPool": {
      const r = i("strides", s, e, t), n = i("pad", s, e, t), o = i("kernelSize", s, e, t);
      return [a.avgPool(i("x", s, e, t), [o[1], o[2]], [r[1], r[2]], n)];
    }
    case "MaxPool": {
      const r = i("strides", s, e, t), n = i("pad", s, e, t), o = i("kernelSize", s, e, t);
      return [a.maxPool(i("x", s, e, t), [o[1], o[2]], [r[1], r[2]], n)];
    }
    case "MaxPoolWithArgmax": {
      const r = i("strides", s, e, t), n = i("pad", s, e, t), o = i("kernelSize", s, e, t), u = i("includeBatchInIndex", s, e, t), { result: l, indexes: p } = a.maxPoolWithArgmax(i("x", s, e, t), [o[1], o[2]], [r[1], r[2]], n, u);
      return [l, p];
    }
    case "AvgPool3D": {
      const r = i("strides", s, e, t), n = i("pad", s, e, t), o = i("kernelSize", s, e, t);
      return [a.avgPool3d(i("x", s, e, t), [o[1], o[2], o[3]], [r[1], r[2], r[3]], n)];
    }
    case "MaxPool3D": {
      const r = i("strides", s, e, t), n = i("pad", s, e, t), o = i("kernelSize", s, e, t);
      return [a.maxPool3d(i("x", s, e, t), [o[1], o[2], o[3]], [r[1], r[2], r[3]], n)];
    }
    case "Dilation2D": {
      const r = i("strides", s, e, t), n = i("pad", s, e, t), o = i("dilations", s, e, t), u = r[1], l = r[2], p = o[1], m = o[2];
      return [a.dilation2d(
        i("x", s, e, t),
        i("filter", s, e, t),
        [u, l],
        n,
        [p, m],
        "NHWC"
        /* dataFormat */
      )];
    }
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Eg = (s, e, t, a = A) => {
  switch (s.op) {
    case "Fill": {
      const r = i("shape", s, e, t), n = i("dtype", s, e, t), o = i("value", s, e, t);
      return [a.fill(r, o, n)];
    }
    case "LinSpace": {
      const r = i("start", s, e, t), n = i("stop", s, e, t), o = i("num", s, e, t);
      return [a.linspace(r, n, o)];
    }
    case "Multinomial": {
      const r = i("logits", s, e, t), n = i("numSamples", s, e, t), o = i("seed", s, e, t);
      return [a.multinomial(r, n, o)];
    }
    case "OneHot": {
      const r = i("indices", s, e, t), n = i("depth", s, e, t), o = i("onValue", s, e, t), u = i("offValue", s, e, t), l = i("dtype", s, e, t);
      return [a.oneHot(r, n, o, u, l)];
    }
    case "Ones":
      return [a.ones(i("shape", s, e, t), i("dtype", s, e, t))];
    case "OnesLike":
      return [a.onesLike(i("x", s, e, t))];
    case "RandomStandardNormal":
      return [a.randomStandardNormal(i("shape", s, e, t), i("dtype", s, e, t), i("seed", s, e, t))];
    case "RandomUniform":
      return [a.randomUniform(
        // tslint:disable-next-line:no-any
        i("shape", s, e, t),
        i("minval", s, e, t),
        i("maxval", s, e, t),
        i("dtype", s, e, t)
      )];
    case "Range": {
      const r = i("start", s, e, t), n = i("stop", s, e, t), o = i("step", s, e, t);
      return [a.range(r, n, o, i("dtype", s, e, t))];
    }
    case "TruncatedNormal": {
      const r = i("shape", s, e, t), n = i("mean", s, e, t), o = i("stdDev", s, e, t), u = i("seed", s, e, t);
      return [a.truncatedNormal(r, n, o, i("dtype", s, e, t), u)];
    }
    case "Zeros":
      return [a.zeros(i("shape", s, e, t), i("dtype", s, e, t))];
    case "ZerosLike":
      return [a.zerosLike(i("x", s, e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function ze(s, e, t) {
  const a = i("boxes", s, e, t), r = i("scores", s, e, t), n = i("maxOutputSize", s, e, t), o = i("iouThreshold", s, e, t), u = i("scoreThreshold", s, e, t), l = i("softNmsSigma", s, e, t);
  return {
    boxes: a,
    scores: r,
    maxOutputSize: n,
    iouThreshold: o,
    scoreThreshold: u,
    softNmsSigma: l
  };
}
const kg = async (s, e, t, a, r = A) => {
  switch (s.op) {
    case "NonMaxSuppressionV5": {
      const { boxes: n, scores: o, maxOutputSize: u, iouThreshold: l, scoreThreshold: p, softNmsSigma: m } = ze(s, e, t), c = await r.image.nonMaxSuppressionWithScoreAsync(n, o, u, l, p, m);
      return [c.selectedIndices, c.selectedScores];
    }
    case "NonMaxSuppressionV4": {
      const { boxes: n, scores: o, maxOutputSize: u, iouThreshold: l, scoreThreshold: p } = ze(s, e, t), m = i("padToMaxOutputSize", s, e, t), c = await r.image.nonMaxSuppressionPaddedAsync(n, o, u, l, p, m);
      return [c.selectedIndices, c.validOutputs];
    }
    case "NonMaxSuppressionV3":
    case "NonMaxSuppressionV2": {
      const { boxes: n, scores: o, maxOutputSize: u, iouThreshold: l, scoreThreshold: p } = ze(s, e, t);
      return [await r.image.nonMaxSuppressionAsync(n, o, u, l, p)];
    }
    case "Where": {
      const n = r.cast(i("condition", s, e, t), "bool"), o = [await r.whereAsync(n)];
      return n.dispose(), o;
    }
    case "ListDiff":
      return r.setdiff1dAsync(i("x", s, e, t), i("y", s, e, t));
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Ig = (s, e, t, a = A) => {
  switch (s.op) {
    case "LowerBound": {
      const r = i("sortedSequence", s, e, t), n = i("values", s, e, t);
      return [a.lowerBound(r, n)];
    }
    case "TopKV2": {
      const r = i("x", s, e, t), n = i("k", s, e, t), o = i("sorted", s, e, t), u = a.topk(r, n, o);
      return [u.values, u.indices];
    }
    case "UpperBound": {
      const r = i("sortedSequence", s, e, t), n = i("values", s, e, t);
      return [a.upperBound(r, n)];
    }
    case "Unique": {
      const r = i("x", s, e, t), n = a.unique(r);
      return [n.values, n.indices];
    }
    case "UniqueV2": {
      const r = i("x", s, e, t), n = i("axis", s, e, t), o = a.unique(r, n);
      return [o.values, o.indices];
    }
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Dg = (s, e, t, a = A) => {
  switch (s.op) {
    case "Const":
      return e[s.name];
    case "PlaceholderWithDefault":
      const r = i("default", s, e, t);
      return [k(s.name, e, t) || r];
    case "Placeholder":
      return [k(s.name, e, t)];
    case "Identity":
    case "StopGradient":
    case "FakeQuantWithMinMaxVars": {
      const m = i("x", s, e, t);
      return [H(m)];
    }
    case "IdentityN":
      return i("x", s, e, t).map((m) => H(m));
    case "Snapshot":
      const n = i("x", s, e, t);
      return [H(n)];
    case "Shape":
      return [a.tensor1d(i("x", s, e, t).shape, "int32")];
    case "ShapeN":
      return i("x", s, e, t).map((m) => a.tensor1d(m.shape));
    case "Size":
      return [a.scalar(i("x", s, e, t).size, "int32")];
    case "Rank":
      return [a.scalar(i("x", s, e, t).rank, "int32")];
    case "NoOp":
      return [a.scalar(1)];
    case "Print":
      const o = i("x", s, e, t), u = i("data", s, e, t), l = i("message", s, e, t), p = i("summarize", s, e, t);
      console.warn("The graph has a tf.print() operation,usually used for debugging, which slows down performance."), console.log(l);
      for (let m = 0; m < u.length; m++)
        console.log(Array.prototype.slice.call(u[m].dataSync()).slice(0, p));
      return [o];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
class $g {
  /**
   * Constructor of HashTable. Creates a hash table.
   *
   * @param keyDType `dtype` of the table keys.
   * @param valueDType `dtype` of the table values.
   */
  constructor(e, t) {
    this.keyDType = e, this.valueDType = t, this.handle = R(0), this.tensorMap = /* @__PURE__ */ new Map(), P(this.handle);
  }
  get id() {
    return this.handle.id;
  }
  /**
   * Dispose the tensors and handle and clear the hashtable.
   */
  clearAndClose() {
    this.tensorMap.forEach((e) => e.dispose()), this.tensorMap.clear(), this.handle.dispose();
  }
  /**
   * The number of items in the hash table.
   */
  size() {
    return this.tensorMap.size;
  }
  /**
   * The number of items in the hash table as a rank-0 tensor.
   */
  tensorSize() {
    return R(this.size(), "int32");
  }
  /**
   * Replaces the contents of the table with the specified keys and values.
   * @param keys Keys to store in the hashtable.
   * @param values Values to store in the hashtable.
   */
  async import(e, t) {
    this.checkKeyAndValueTensor(e, t);
    const a = await e.data();
    return this.tensorMap.forEach((r) => r.dispose()), this.tensorMap.clear(), z(() => {
      const r = ae(t), n = a.length, o = r.length;
      N(n === o, () => `The number of elements doesn't match, keys has ${n} elements, the values has ${o} elements.`);
      for (let u = 0; u < n; u++) {
        const l = a[u], p = r[u];
        P(p), this.tensorMap.set(l, p);
      }
      return this.handle;
    });
  }
  /**
   * Looks up keys in a hash table, outputs the corresponding values.
   *
   * Performs batch lookups, for every element in the key tensor, `find`
   * stacks the corresponding value into the return tensor.
   *
   * If an element is not present in the table, the given `defaultValue` is
   * used.
   *
   * @param keys Keys to look up. Must have the same type as the keys of the
   *     table.
   * @param defaultValue The scalar `defaultValue` is the value output for keys
   *     not present in the table. It must also be of the same type as the
   *     table values.
   */
  async find(e, t) {
    this.checkKeyAndValueTensor(e, t);
    const a = await e.data();
    return z(() => {
      const r = [];
      for (let n = 0; n < a.length; n++) {
        const o = a[n], u = this.findWithDefault(o, t);
        r.push(u);
      }
      return ee(r);
    });
  }
  // tslint:disable-next-line: no-any
  findWithDefault(e, t) {
    const a = this.tensorMap.get(e);
    return a ?? t;
  }
  checkKeyAndValueTensor(e, t) {
    if (e.dtype !== this.keyDType)
      throw new Error(`Expect key dtype ${this.keyDType}, but got ${e.dtype}`);
    if (t.dtype !== this.valueDType)
      throw new Error(`Expect value dtype ${this.valueDType}, but got ${t.dtype}`);
  }
}
/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Cg = async (s, e, t, a) => {
  switch (s.op) {
    case "HashTable":
    case "HashTableV2": {
      const r = a.getHashTableHandleByName(s.name);
      if (r != null)
        return [r];
      {
        const n = i("keyDType", s, e, t), o = i("valueDType", s, e, t), u = new $g(n, o);
        return a.addHashTable(s.name, u), [u.handle];
      }
    }
    case "InitializeTable":
    case "InitializeTableV2":
    case "LookupTableImport":
    case "LookupTableImportV2": {
      const r = i("tableHandle", s, e, t, a), n = i("keys", s, e, t), o = i("values", s, e, t);
      return [await a.getHashTableById(r.id).import(n, o)];
    }
    case "LookupTableFind":
    case "LookupTableFindV2": {
      const r = i("tableHandle", s, e, t, a), n = i("keys", s, e, t), o = i("defaultValue", s, e, t);
      return [await a.getHashTableById(r.id).find(n, o)];
    }
    case "LookupTableSize":
    case "LookupTableSizeV2": {
      const r = i("tableHandle", s, e, t, a);
      return [a.getHashTableById(r.id).tensorSize()];
    }
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const zg = (s, e, t, a = A) => {
  switch (s.op) {
    case "ResizeBilinear": {
      const r = i("images", s, e, t), n = i("size", s, e, t), o = i("alignCorners", s, e, t), u = i("halfPixelCenters", s, e, t);
      return [a.image.resizeBilinear(r, [n[0], n[1]], o, u)];
    }
    case "ResizeNearestNeighbor": {
      const r = i("images", s, e, t), n = i("size", s, e, t), o = i("alignCorners", s, e, t), u = i("halfPixelCenters", s, e, t);
      return [a.image.resizeNearestNeighbor(r, [n[0], n[1]], o, u)];
    }
    case "CropAndResize": {
      const r = i("image", s, e, t), n = i("boxes", s, e, t), o = i("boxInd", s, e, t), u = i("cropSize", s, e, t), l = i("method", s, e, t), p = i("extrapolationValue", s, e, t);
      return [a.image.cropAndResize(r, n, o, u, l, p)];
    }
    case "ImageProjectiveTransformV3": {
      const r = i("images", s, e, t), n = i("transforms", s, e, t), o = i("outputShape", s, e, t), u = i("fillValue", s, e, t), l = i("interpolation", s, e, t), p = i("fillMode", s, e, t);
      return [a.image.transform(r, n, l.toLowerCase(), p.toLowerCase(), u, o)];
    }
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const xg = (s, e, t, a = A) => {
  switch (s.op) {
    case "Equal":
      return [a.equal(i("a", s, e, t), i("b", s, e, t))];
    case "NotEqual":
      return [a.notEqual(i("a", s, e, t), i("b", s, e, t))];
    case "Greater":
      return [a.greater(i("a", s, e, t), i("b", s, e, t))];
    case "GreaterEqual":
      return [a.greaterEqual(i("a", s, e, t), i("b", s, e, t))];
    case "Less":
      return [a.less(i("a", s, e, t), i("b", s, e, t))];
    case "LessEqual":
      return [a.lessEqual(i("a", s, e, t), i("b", s, e, t))];
    case "LogicalAnd":
      return [a.logicalAnd(i("a", s, e, t), i("b", s, e, t))];
    case "LogicalNot":
      return [a.logicalNot(i("a", s, e, t))];
    case "LogicalOr":
      return [a.logicalOr(i("a", s, e, t), i("b", s, e, t))];
    case "Select":
    case "SelectV2":
      return [a.where(i("condition", s, e, t), i("a", s, e, t), i("b", s, e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Lg = (s, e, t, a = A) => {
  switch (s.op) {
    case "BatchMatMul":
    case "BatchMatMulV2":
    case "MatMul":
      return [a.matMul(i("a", s, e, t), i("b", s, e, t), i("transposeA", s, e, t), i("transposeB", s, e, t))];
    case "Einsum":
      return [a.einsum(i("equation", s, e, t), ...i("tensors", s, e, t))];
    case "Transpose":
      return [a.transpose(i("x", s, e, t), i("perm", s, e, t))];
    case "_FusedMatMul":
      const [r, n] = i("fusedOps", s, e, t), o = r === "biasadd", u = n === "prelu", l = i("numArgs", s, e, t), p = i("leakyreluAlpha", s, e, t);
      if (o) {
        if (u && l !== 2)
          throw new Error("Fused MatMul with BiasAdd and Prelu must have two extra arguments: bias and alpha.");
        if (!u && l !== 1)
          throw new Error("Fused MatMul with BiasAdd must have one extra argument: bias.");
      }
      const [m, c] = i("args", s, e, t);
      return [a.fused.matMul({
        a: i("a", s, e, t),
        b: i("b", s, e, t),
        transposeA: i("transposeA", s, e, t),
        transposeB: i("transposeB", s, e, t),
        bias: m,
        activation: n,
        preluActivationWeights: c,
        leakyreluAlpha: p
      })];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Vg = (s, e, t, a = A) => {
  switch (s.op) {
    case "EuclideanNorm":
      return [a.euclideanNorm(i("x", s, e, t), i("axis", s, e, t), i("keepDims", s, e, t))];
    case "FusedBatchNorm":
    case "FusedBatchNormV2":
      return [a.batchNorm(i("x", s, e, t), i("mean", s, e, t), i("variance", s, e, t), i("offset", s, e, t), i("scale", s, e, t), i("epsilon", s, e, t))];
    case "FusedBatchNormV3":
      return [a.batchNorm(i("x", s, e, t), i("mean", s, e, t), i("variance", s, e, t), i("offset", s, e, t), i("scale", s, e, t), i("epsilon", s, e, t))];
    case "LRN":
      return [a.localResponseNormalization(i("x", s, e, t), i("radius", s, e, t), i("bias", s, e, t), i("alpha", s, e, t), i("beta", s, e, t))];
    case "Softmax":
      return [a.softmax(i("x", s, e, t))];
    case "LogSoftmax":
      return [a.logSoftmax(i("x", s, e, t))];
    case "SparseToDense":
      return [a.sparseToDense(i("sparseIndices", s, e, t), i("outputShape", s, e, t), i("sparseValues", s, e, t), i("defaultValue", s, e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2022 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Fg = (s, e, t, a = A) => {
  switch (s.op) {
    case "RaggedGather": {
      const { outputNestedSplits: r, outputDenseValues: n } = a.raggedGather(i("paramsNestedSplits", s, e, t), i("paramsDenseValues", s, e, t), i("indices", s, e, t), i("outputRaggedRank", s, e, t));
      return r.concat(n);
    }
    case "RaggedRange": {
      const { rtNestedSplits: r, rtDenseValues: n } = a.raggedRange(i("starts", s, e, t), i("limits", s, e, t), i("splits", s, e, t));
      return [r, n];
    }
    case "RaggedTensorToTensor":
      return [a.raggedTensorToTensor(i("shape", s, e, t), i("values", s, e, t), i("defaultValue", s, e, t), i("rowPartitionTensors", s, e, t), i("rowPartitionTypes", s, e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Pg = (s, e, t, a = A) => {
  switch (s.op) {
    case "Max": {
      const u = i("axis", s, e, t), l = i("keepDims", s, e, t);
      return [a.max(i("x", s, e, t), u, l)];
    }
    case "Mean": {
      const u = i("axis", s, e, t), l = i("keepDims", s, e, t);
      return [a.mean(i("x", s, e, t), u, l)];
    }
    case "Min": {
      const u = i("axis", s, e, t), l = i("keepDims", s, e, t);
      return [a.min(i("x", s, e, t), u, l)];
    }
    case "Sum": {
      const u = i("axis", s, e, t), l = i("keepDims", s, e, t);
      return [a.sum(i("x", s, e, t), u, l)];
    }
    case "All": {
      const u = i("axis", s, e, t), l = i("keepDims", s, e, t);
      return [a.all(i("x", s, e, t), u, l)];
    }
    case "Any": {
      const u = i("axis", s, e, t), l = i("keepDims", s, e, t);
      return [a.any(i("x", s, e, t), u, l)];
    }
    case "ArgMax": {
      const u = i("axis", s, e, t);
      return [a.argMax(i("x", s, e, t), u)];
    }
    case "ArgMin": {
      const u = i("axis", s, e, t);
      return [a.argMin(i("x", s, e, t), u)];
    }
    case "Prod": {
      const u = i("axis", s, e, t), l = i("keepDims", s, e, t);
      return [a.prod(i("x", s, e, t), u, l)];
    }
    case "Cumprod": {
      const u = i("axis", s, e, t), l = i("exclusive", s, e, t), p = i("reverse", s, e, t);
      return [a.cumprod(i("x", s, e, t), u, l, p)];
    }
    case "Cumsum": {
      const u = i("axis", s, e, t), l = i("exclusive", s, e, t), p = i("reverse", s, e, t);
      return [a.cumsum(i("x", s, e, t), u, l, p)];
    }
    case "Bincount":
      const r = i("x", s, e, t), n = i("weights", s, e, t), o = i("size", s, e, t);
      return [a.bincount(r, n, o)];
    case "DenseBincount": {
      const u = i("x", s, e, t), l = i("weights", s, e, t), p = i("size", s, e, t), m = i("binaryOutput", s, e, t);
      return [a.denseBincount(u, l, p, m)];
    }
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Rg = (s, e, t, a = A) => {
  switch (s.op) {
    case "ConcatV2":
    case "Concat": {
      const r = i("n", s, e, t), n = i("axis", s, e, t);
      let o = i("tensors", s, e, t);
      return o = o.slice(0, r), [a.concat(o, n)];
    }
    case "Gather": {
      const r = i("x", s, e, t), n = i("indices", s, e, t);
      return [a.gather(r, a.cast(n, "int32"), 0)];
    }
    case "GatherV2": {
      const r = i("axis", s, e, t), n = i("batchDims", s, e, t), o = i("x", s, e, t), u = i("indices", s, e, t);
      return [a.gather(o, a.cast(u, "int32"), r, n)];
    }
    case "Reverse": {
      const r = i("dims", s, e, t), n = [];
      for (let u = 0; u < r.length; u++)
        r[u] && n.push(u);
      const o = i("x", s, e, t);
      return [a.reverse(o, n)];
    }
    case "ReverseV2": {
      const r = i("axis", s, e, t), n = i("x", s, e, t);
      return [a.reverse(n, r)];
    }
    case "Slice": {
      const r = i("begin", s, e, t), n = i("size", s, e, t);
      return [a.slice(i("x", s, e, t), r, n)];
    }
    case "StridedSlice": {
      const r = i("begin", s, e, t), n = i("end", s, e, t), o = i("strides", s, e, t), u = i("beginMask", s, e, t), l = i("endMask", s, e, t), p = i("ellipsisMask", s, e, t), m = i("newAxisMask", s, e, t), c = i("shrinkAxisMask", s, e, t), d = i("x", s, e, t);
      return [a.stridedSlice(d, r, n, o, u, l, p, m, c)];
    }
    case "Pack":
      return z(() => {
        const r = i("axis", s, e, t), n = i("tensors", s, e, t), o = n[0].shape, u = a.squeeze(n[0]).shape, l = n.map((p) => {
          const m = ue(p.shape, o);
          if (!m && !ue(a.squeeze(p).shape, u))
            throw new Error("the input tensors shape does not match");
          return m ? p : a.reshape(p, o);
        });
        return [a.stack(l, r)];
      });
    case "Unpack": {
      const r = i("axis", s, e, t), n = i("tensor", s, e, t);
      return a.unstack(n, r);
    }
    case "Tile": {
      const r = i("reps", s, e, t);
      return [a.tile(i("x", s, e, t), r)];
    }
    case "Split":
    case "SplitV": {
      const r = i("axis", s, e, t), n = i("numOrSizeSplits", s, e, t), o = i("x", s, e, t);
      return a.split(o, n, r);
    }
    case "ScatterNd": {
      const r = i("indices", s, e, t), n = i("values", s, e, t), o = i("shape", s, e, t);
      return [a.scatterND(r, n, o)];
    }
    case "GatherNd": {
      const r = i("x", s, e, t), n = i("indices", s, e, t);
      return [a.gatherND(r, n)];
    }
    case "SparseToDense": {
      const r = i("sparseIndices", s, e, t), n = i("outputShape", s, e, t), o = i("sparseValues", s, e, t), u = i("defaultValue", s, e, t);
      return [a.sparseToDense(r, o, n, o.dtype === u.dtype ? u : a.cast(u, o.dtype))];
    }
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2021 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const jg = (s, e, t, a = A) => {
  switch (s.op) {
    case "SparseFillEmptyRows": {
      const { outputIndices: r, outputValues: n, emptyRowIndicator: o, reverseIndexMap: u } = a.sparse.sparseFillEmptyRows(i("indices", s, e, t), i("values", s, e, t), i("denseShape", s, e, t), i("defaultValue", s, e, t));
      return [
        r,
        n,
        o,
        u
      ];
    }
    case "SparseReshape": {
      const { outputIndices: r, outputShape: n } = a.sparse.sparseReshape(i("inputIndices", s, e, t), i("inputShape", s, e, t), i("newShape", s, e, t));
      return [r, n];
    }
    case "SparseSegmentMean":
      return [a.sparse.sparseSegmentMean(i("data", s, e, t), i("indices", s, e, t), i("segmentIds", s, e, t))];
    case "SparseSegmentSum":
      return [a.sparse.sparseSegmentSum(i("data", s, e, t), i("indices", s, e, t), i("segmentIds", s, e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Bg = (s, e, t, a = A) => {
  switch (s.op) {
    case "FFT":
      return [a.fft(i("x", s, e, t))];
    case "IFFT":
      return [a.ifft(i("x", s, e, t))];
    case "RFFT":
      return [a.rfft(i("x", s, e, t))];
    case "IRFFT":
      return [a.irfft(i("x", s, e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2021 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Hg = (s, e, t, a = A) => {
  switch (s.op) {
    case "StringNGrams": {
      const { nGrams: r, nGramsSplits: n } = a.string.stringNGrams(i("data", s, e, t), i("dataSplits", s, e, t), i("separator", s, e, t), i("nGramWidths", s, e, t), i("leftPad", s, e, t), i("rightPad", s, e, t), i("padWidth", s, e, t), i("preserveShortSequences", s, e, t));
      return [r, n];
    }
    case "StringSplit": {
      const { indices: r, values: n, shape: o } = a.string.stringSplit(i("input", s, e, t), i("delimiter", s, e, t), i("skipEmpty", s, e, t));
      return [r, n, o];
    }
    case "StringToHashBucketFast":
      return [a.string.stringToHashBucketFast(i("input", s, e, t), i("numBuckets", s, e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Wg = (s, e, t, a = A) => {
  switch (s.op) {
    case "Cast":
      return [a.cast(i("x", s, e, t), i("dtype", s, e, t))];
    case "ExpandDims": {
      const r = i("axis", s, e, t);
      return [a.expandDims(i("x", s, e, t), r)];
    }
    case "Squeeze": {
      const r = i("axis", s, e, t);
      return [a.squeeze(i("x", s, e, t), r)];
    }
    case "Reshape":
      return [a.reshape(i("x", s, e, t), i("shape", s, e, t))];
    case "MirrorPad":
      return [a.mirrorPad(i("x", s, e, t), i("padding", s, e, t), i("mode", s, e, t))];
    case "PadV2":
    case "Pad":
      return [a.pad(i("x", s, e, t), i("padding", s, e, t), i("constantValue", s, e, t))];
    case "SpaceToBatchND": {
      const r = i("blockShape", s, e, t), n = i("paddings", s, e, t);
      return [a.spaceToBatchND(i("x", s, e, t), r, n)];
    }
    case "BatchToSpaceND": {
      const r = i("blockShape", s, e, t), n = i("crops", s, e, t);
      return [a.batchToSpaceND(i("x", s, e, t), r, n)];
    }
    case "DepthToSpace": {
      const r = i("blockSize", s, e, t), n = i("dataFormat", s, e, t).toUpperCase();
      return [a.depthToSpace(i("x", s, e, t), r, n)];
    }
    case "BroadcastTo":
      return [a.broadcastTo(i("x", s, e, t), i("shape", s, e, t))];
    case "BroadcastArgs":
      return [a.broadcastArgs(i("s0", s, e, t), i("s1", s, e, t))];
    default:
      throw TypeError(`Node type ${s.op} is not implemented`);
  }
};
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function jt(s, e, t, a, r = z) {
  const n = ((o, u, l) => {
    switch (o.category) {
      case "arithmetic":
        return r(() => bg(o, u, l));
      case "basic_math":
        return r(() => Ng(o, u, l));
      case "control":
        return _g(o, u, l);
      case "convolution":
        return r(() => Ag(o, u, l));
      case "creation":
        return r(() => Eg(o, u, l));
      case "dynamic":
        return kg(o, u, l);
      case "evaluation":
        return r(() => Ig(o, u, l));
      case "image":
        return r(() => zg(o, u, l));
      case "graph":
        return r(() => Dg(o, u, l));
      case "logical":
        return r(() => xg(o, u, l));
      case "matrices":
        return r(() => Lg(o, u, l));
      case "normalization":
        return r(() => Vg(o, u, l));
      case "ragged":
        return r(() => Fg(o, u, l));
      case "reduction":
        return r(() => Pg(o, u, l));
      case "slice_join":
        return r(() => Rg(o, u, l));
      case "sparse":
        return r(() => jg(o, u, l));
      case "spectral":
        return r(() => Bg(o, u, l));
      case "string":
        return r(() => Hg(o, u, l));
      case "transformation":
        return r(() => Wg(o, u, l));
      case "hash_table":
        return Cg(o, u, l, a);
      case "custom":
        const p = Jn(o.op);
        if (p && p.customExecutor)
          return p.customExecutor(new gg(o, u, l));
        throw TypeError(`Custom op ${o.op} is not registered.`);
      default:
        throw TypeError(`Unknown op '${o.op}'. File an issue at https://github.com/tensorflow/tfjs/issues so we can add it, or register a custom execution with tf.registerOp()`);
    }
  })(s, e, t);
  return Oe(n) ? n.then((o) => [].concat(o)) : [].concat(n);
}
class Bt {
  constructor(e = {}, t = {}, a = {}, r = {}) {
    this.weightMap = e, this.tensorArrayMap = t, this.tensorListMap = a, this.functionMap = r, this.rootContext = { id: 0, frameName: "", iterationId: 0 }, this.contexts = [this.rootContext], this.lastId = 0, this.generateCurrentContextIds();
  }
  newFrame(e, t) {
    return { id: e, frameName: t, iterationId: 0 };
  }
  /**
   * Set the current context
   * @param contexts: ExecutionContextInfo[] the current path of execution
   * frames
   */
  set currentContext(e) {
    this.contexts !== e && (this.contexts = e, this.generateCurrentContextIds());
  }
  get currentContext() {
    return this.contexts;
  }
  /**
   * Returns the current context in string format.
   */
  get currentContextId() {
    return this._currentContextIds[0];
  }
  /**
   * Returns the current context and all parent contexts in string format.
   * This allow access to the nodes in the current and parent frames.
   */
  get currentContextIds() {
    return this._currentContextIds;
  }
  generateCurrentContextIds() {
    const e = [];
    for (let t = 0; t < this.contexts.length - 1; t++) {
      const a = this.contexts.slice(0, this.contexts.length - t);
      e.push(this.contextIdforContexts(a));
    }
    e.push(""), this._currentContextIds = e;
  }
  contextIdforContexts(e) {
    return e ? e.map((t) => t.id === 0 && t.iterationId === 0 ? "" : `${t.frameName}-${t.iterationId}`).join("/") : "";
  }
  /**
   * Enter a new frame, a new context is pushed on the current context list.
   * @param frameId new frame id
   */
  enterFrame(e) {
    this.contexts && (this.lastId++, this.contexts = this.contexts.slice(), this.contexts.push(this.newFrame(this.lastId, e)), this._currentContextIds.unshift(this.contextIdforContexts(this.contexts)));
  }
  /**
   * Exit the current frame, the last context is removed from the current
   * context list.
   */
  exitFrame() {
    if (this.contexts && this.contexts.length > 1)
      this.contexts = this.contexts.slice(), this.contexts.splice(-1), this.currentContextIds.shift();
    else
      throw new Error("Cannot exit frame, the context is empty");
  }
  /**
   * Enter the next iteration of a loop, the iteration id of last context is
   * increased.
   */
  nextIteration() {
    if (this.contexts && this.contexts.length > 0) {
      this.contexts = this.contexts.slice(), this.lastId++;
      const e = Object.assign({}, this.contexts[this.contexts.length - 1]);
      e.iterationId += 1, e.id = this.lastId, this.contexts.splice(-1, 1, e), this._currentContextIds.splice(0, 1, this.contextIdforContexts(this.contexts));
    } else
      throw new Error("Cannot increase frame iteration, the context is empty");
  }
  getWeight(e) {
    return this.weightMap[e];
  }
  addTensorArray(e) {
    this.tensorArrayMap[e.id] = e;
  }
  getTensorArray(e) {
    return this.tensorArrayMap[e];
  }
  addTensorList(e) {
    this.tensorListMap[e.id] = e;
  }
  getTensorList(e) {
    return this.tensorListMap[e];
  }
  dispose(e) {
    for (const t in this.tensorArrayMap)
      this.tensorArrayMap[t].clearAndClose(e);
    for (const t in this.tensorListMap)
      this.tensorListMap[t].clearAndClose(e);
  }
}
/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
function Ht(s, e, t, a) {
  const r = /* @__PURE__ */ new Set(), n = [];
  let o = null, u = null;
  const l = /* @__PURE__ */ new Set(), p = Object.keys(s).map((d) => $(d)[0]);
  let m = [];
  a != null && (m = a.map((d) => $(d.name)[0]));
  const c = [...e];
  for (; c.length > 0; ) {
    const d = c.pop();
    if ((Zn(d) || Jg(d) || Qg(d)) && o == null && (o = d, u = o.children.map((h) => h.name).filter((h) => r.has(h))), r.add(d.name), t[d.name] == null && p.indexOf(d.name) === -1 && m.indexOf(d.name) === -1) {
      if (d.inputs.length === 0) {
        n.push(d.name);
        continue;
      }
      d.inputs.forEach((h) => {
        l.has(h.name) || (l.add(h.name), c.push(h));
      });
    }
  }
  return { inputs: s, outputs: e, usedNodes: r, missingInputs: n, dynamicNode: o, syncInputs: u };
}
function qg(s, e, t) {
  const { usedNodes: a, inputs: r } = t, n = [], o = Object.keys(r).map((m) => $(m)[0]).map((m) => s.nodes[m]), u = s.initNodes;
  o.forEach((m) => {
    a.has(m.name) && n.push(m);
  }), s.weights.forEach((m) => {
    a.has(m.name) && n.push(m);
  }), u != null && u.forEach((m) => {
    a.has(m.name) && n.push(m);
  });
  const l = /* @__PURE__ */ new Set(), p = [];
  for (; n.length > 0; ) {
    const m = n.pop();
    l.add(m.name), e[m.name] || p.push(m), m.children.forEach((c) => {
      !l.has(c.name) && a.has(c.name) && c.inputs.every((d) => l.has(d.name)) && n.push(c);
    });
  }
  return p;
}
const Ug = [
  "Switch",
  "Merge",
  "Enter",
  "Exit",
  "NextIteration",
  "StatelessIf",
  "StatelessWhile",
  "if",
  "While"
], Gg = [
  "NonMaxSuppressionV2",
  "NonMaxSuppressionV3",
  "NonMaxSuppressionV5",
  "Where"
], Kg = [
  "HashTable",
  "HashTableV2",
  "LookupTableImport",
  "LookupTableImportV2",
  "LookupTableFind",
  "LookupTableFindV2",
  "LookupTableSize",
  "LookupTableSizeV2"
];
function Zn(s) {
  return Ug.indexOf(s.op) >= 0;
}
function Jg(s) {
  return Gg.indexOf(s.op) >= 0;
}
function Qg(s) {
  return Kg.indexOf(s.op) >= 0;
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
class Ee {
  /**
   *
   * @param graph Graph the model or function graph to be executed.
   * @param parent When building function exector you need to set the parent
   * executor. Since the weights and function executor maps are set at parant
   * level, that function executor can access the function maps and weight maps
   * through the parent.
   */
  constructor(e, t) {
    this.graph = e, this.parent = t, this.compiledMap = /* @__PURE__ */ new Map(), this._weightMap = {}, this.SEPERATOR = ",", this._functions = {}, this._functionExecutorMap = {}, this.keepIntermediateTensors = !1, this._outputs = e.outputs, this._inputs = e.inputs, this._initNodes = e.initNodes, this._signature = e.signature, this._functions = e.functions, e.functions != null && Object.keys(e.functions).forEach((a) => {
      this._functionExecutorMap[a] = new Ee(e.functions[a], this);
    });
  }
  get weightIds() {
    return this.parent ? this.parent.weightIds : this._weightIds;
  }
  get functionExecutorMap() {
    return this.parent ? this.parent.functionExecutorMap : this._functionExecutorMap;
  }
  get weightMap() {
    return this.parent ? this.parent.weightMap : this._weightMap;
  }
  set weightMap(e) {
    const t = Object.keys(e).map((a) => e[a].map((r) => r.id));
    this._weightIds = [].concat(...t), this._weightMap = e;
  }
  /**
   * Set `ResourceManager` shared by executors of a model.
   * @param resourceManager: `ResourceManager` of the `GraphModel`.
   */
  set resourceManager(e) {
    this._resourceManager = e;
  }
  get inputs() {
    return this._inputs.map((e) => ({
      name: e.name,
      shape: e.attrParams.shape ? e.attrParams.shape.value : void 0,
      dtype: e.attrParams.dtype ? e.attrParams.dtype.value : void 0
    }));
  }
  get outputs() {
    return this._outputs.map((e) => ({
      name: e.name,
      shape: e.attrParams.shape ? e.attrParams.shape.value : void 0,
      dtype: e.attrParams.dtype ? e.attrParams.dtype.value : void 0
    }));
  }
  get inputNodes() {
    return this._inputs.map((e) => e.signatureKey || e.name);
  }
  get outputNodes() {
    return this._outputs.map((e) => {
      const t = e.signatureKey || e.name;
      return e.defaultOutput ? `${t}:${e.defaultOutput}` : t;
    });
  }
  get functions() {
    return Object.keys(this._functions).reduce((e, t) => (e[t] = this._functions[t].signature, e), {});
  }
  getCompilationKey(e, t) {
    const a = e.map((n) => n.name).sort(), r = t.map((n) => n.name).sort();
    return a.join(this.SEPERATOR) + "--" + r.join(this.SEPERATOR);
  }
  /**
   * Compiles the inference graph and returns the minimal set of nodes that are
   * required for execution, in the correct execution order.
   */
  compile(e, t) {
    const a = Ht(e, t, this.weightMap, this._initNodes), { missingInputs: r, dynamicNode: n, syncInputs: o } = a;
    if (n != null)
      throw new Error(`This execution contains the node '${n.name}', which has the dynamic op '${n.op}'. Please use model.executeAsync() instead. Alternatively, to avoid the dynamic ops, specify the inputs [${o}]`);
    if (r.length > 0) {
      const u = t.map((p) => p.name), l = Object.keys(e);
      throw new Error(`Cannot compute the outputs [${u}] from the provided inputs [${l}]. Missing the following inputs: [${r}]`);
    }
    return qg(this.graph, this.weightMap, a);
  }
  cloneAndKeepTensor(e) {
    if (e == null)
      return null;
    const t = e.clone();
    return P(t), t;
  }
  cloneTensorList(e) {
    return e ? e.map((a) => this.cloneAndKeepTensor(a)) : null;
  }
  cloneTensorMap(e) {
    return Object.fromEntries(Object.entries(e).map(([t, a]) => [t, this.cloneTensorList(a)]));
  }
  /**
   * Executes the inference for given input tensors.
   * @param inputs Tensor map for the model inputs, keyed by the input node
   * names.
   * @param outputs Optional. output node name from the Tensorflow model, if
   * no outputs are specified, the default outputs of the model would be used.
   * You can inspect intermediate nodes of the model by adding them to the
   * outputs array.
   */
  execute(e, t) {
    this.disposeIntermediateTensors(), e = this.mapInputs(e);
    const a = Object.keys(e).sort();
    this.checkInputs(e), this.checkInputShapeAndType(e), t = this.mapOutputs(t), this.checkOutputs(t);
    const r = a.map((c) => this.graph.nodes[$(c)[0]]), n = t.map((c) => $(c)[0]);
    let o = n.map((c) => this.graph.nodes[c]);
    o.length === 0 && (o = this._outputs);
    const u = this.getCompilationKey(r, o);
    let l = this.compiledMap.get(u);
    l == null && (l = this.compile(e, o), this.compiledMap.set(u, l));
    try {
      this.keepIntermediateTensors = x().getBool("KEEP_INTERMEDIATE_TENSORS");
    } catch (c) {
      this.keepIntermediateTensors = !1, console.warn(c.message);
    }
    const p = {}, m = {};
    return z(() => {
      const c = new Bt(this.weightMap, p, m, this.functionExecutorMap), d = Object.assign({}, this.weightMap);
      this.keepIntermediateTensors && (this.clonedTensorsMap = this.cloneTensorMap(this.weightMap)), Object.keys(e).forEach((f) => {
        const [y, T] = $(f), _ = [];
        _[T] = e[f], d[y] = _, this.keepIntermediateTensors && (this.clonedTensorsMap[y] = this.cloneTensorList(_));
      });
      const h = this.getFrozenTensorIds(d), b = {};
      for (let f = 0; f < l.length; f++) {
        const y = l[f];
        if (!d[y.name]) {
          const T = jt(y, d, c, this._resourceManager);
          if (Oe(T))
            throw new Error(`The execution of the op '${y.op}' returned a promise. Please use model.executeAsync() instead.`);
          d[y.name] = T, this.keepIntermediateTensors && (this.clonedTensorsMap[y.name] = this.cloneTensorList(T)), this.checkTensorForDisposal(y.name, y, d, c, h, n, b);
        }
      }
      return this.parent == null && c.dispose(h), t.map((f) => k(f, d, c));
    });
  }
  getFrozenTensorIds(e) {
    const t = [].concat.apply([], Object.keys(e).map((a) => e[a]).map((a) => a.map((r) => r.id)));
    return new Set(t);
  }
  checkTensorForDisposal(e, t, a, r, n, o, u) {
    t.category === "control" || o.indexOf(e) !== -1 || (a[e].forEach((l) => {
      l != null && (u[l.id] = (u[l.id] || 0) + t.children.length);
    }), t.inputs.forEach((l) => {
      if (l.category !== "control") {
        const p = $y(l.name, a, r);
        p != null && p.forEach((m) => {
          if (m && !m.kept && !n.has(m.id)) {
            const c = u[m.id];
            c === 1 ? (m.dispose(), delete u[m.id]) : c != null && u[m.id]--;
          }
        });
      }
    }));
  }
  /**
   * Executes the inference for given input tensors in Async fashion.
   * @param inputs Tensor map for the model inputs, keyed by the input node
   * names.
   * @param outputs output node name from the Tensorflow model, if no outputs
   * are specified, the default outputs of the model would be used. You can
   * inspect intermediate nodes of the model by adding them to the outputs
   * array.
   */
  async executeAsync(e, t) {
    return this._executeAsync(e, t);
  }
  disposeIntermediateTensors() {
    this.clonedTensorsMap && (Object.values(this.clonedTensorsMap).forEach((e) => {
      for (const t of e)
        t && !t.isDisposed && t.dispose();
    }), this.clonedTensorsMap = null);
  }
  getIntermediateTensors() {
    return this.clonedTensorsMap;
  }
  /**
   * Executes the inference for given input tensors in Async fashion.
   * @param inputs Tensor map for the model inputs, keyed by the input node
   * names.
   * @param outputs Optional. output node name from the Tensorflow model,
   * if no outputs are specified, the default outputs of the model would be
   * used. You can inspect intermediate nodes of the model by adding them to
   * the outputs array.
   * @param isFunctionExecution Optional. Flag for executing a function.
   * @param tensorArrayMap Optional, global TensorArray map by id. Used for
   * function execution.
   * @param tensorArrayMap Optinal global TensorList map by id. Used for
   * function execution.
   */
  async _executeAsync(e, t, a = !1, r = {}, n = {}) {
    this.disposeIntermediateTensors(), a || (e = this.mapInputs(e), this.checkInputs(e), this.checkInputShapeAndType(e), t = this.mapOutputs(t), this.checkOutputs(t));
    try {
      this.keepIntermediateTensors = x().getBool("KEEP_INTERMEDIATE_TENSORS");
    } catch (d) {
      this.keepIntermediateTensors = !1, console.warn(d.message);
    }
    const o = new Bt(this.weightMap, r, n, this.functionExecutorMap);
    this.keepIntermediateTensors && (this.clonedTensorsMap = this.cloneTensorMap(this.weightMap));
    const u = await this.executeWithControlFlow(e, o, t, a), l = t.map((d) => k(d, u, o)), p = l.map((d) => d.id), m = Object.keys(e).map((d) => e[d].id), c = /* @__PURE__ */ new Set([...p, ...m, ...this.weightIds]);
    return Object.values(u).forEach((d) => {
      d.forEach((h) => {
        h && !h.isDisposed && !c.has(h.id) && h.dispose();
      });
    }), this.parent == null && o.dispose(c), l;
  }
  async executeFunctionAsync(e, t, a) {
    const r = e.reduce((n, o, u) => (n[this.inputs[u].name] = o, n), {});
    return this._executeAsync(r, this.outputNodes, !0, t, a);
  }
  /**
   * When there are control flow nodes in the graph, the graph execution use
   * ExecutionContext to keep track of the frames and loop iterators.
   * @param inputs placeholder tensors for the graph.
   * @param context the execution context object for current execution.
   * @param outputNames Optional. output node name from the Tensorflow model,
   * if no outputs are specified, the default outputs of the model would be
   * used. You can inspect intermediate nodes of the model by adding them to
   * the outputs array.
   * @param isFunctionExecution Flag for executing a function.
   */
  async executeWithControlFlow(e, t, a, r) {
    const n = Object.keys(e), o = n.map((w) => this.graph.nodes[$(w)[0]]), u = a.map((w) => $(w)[0]);
    let l = u.map((w) => this.graph.nodes[w]);
    l.length === 0 && (l = this._outputs);
    const { usedNodes: p, missingInputs: m, dynamicNode: c, syncInputs: d } = Ht(e, l, this.weightMap, this._initNodes), h = [
      ...o,
      ...this.graph.weights,
      ...this._initNodes || []
    ].map((w) => ({ node: w, contexts: t.currentContext })), b = Object.assign({}, this.weightMap);
    Object.keys(e).forEach((w) => {
      const [I, E] = $(w), D = [];
      D[E] = e[w], b[I] = D;
    });
    const f = {}, y = this.getFrozenTensorIds(b), T = {};
    for (; h.length > 0; ) {
      const w = this.processStack(o, h, t, b, T, y, u, f, p);
      await Promise.all(w);
    }
    c == null && !r && console.warn("This model execution did not contain any nodes with control flow or dynamic output shapes. You can use model.execute() instead.");
    const _ = l.filter((w) => !Zn(w) && !k(w.name, b, t)).map((w) => w.name);
    if (_.length > 0) {
      let w = "";
      throw c != null && (w = `Alternatively, to avoid the dynamic ops, use model.execute() and specify the inputs [${d}]`), new Error(`Cannot compute the outputs [${_}] from the provided inputs [${n}]. Consider providing the following inputs: [${m}]. ${w}`);
    }
    return b;
  }
  processStack(e, t, a, r, n, o, u, l, p) {
    const m = [];
    for (; t.length > 0; ) {
      const c = t.pop();
      a.currentContext = c.contexts;
      let d = "";
      if (c.node.op === "Enter" && i("isConstant", c.node, r, a) && ([d] = B(c.node.name, a)), r[c.node.name] == null) {
        const h = jt(c.node, r, a, this._resourceManager);
        d || ([d] = B(c.node.name, a));
        const b = a.currentContext;
        Oe(h) ? m.push(h.then((f) => (r[d] = f, this.keepIntermediateTensors && (this.clonedTensorsMap[d] = this.cloneTensorList(f)), a.currentContext = b, this.checkTensorForDisposal(d, c.node, r, a, o, u, l), this.processChildNodes(c.node, t, a, r, n, p), f))) : (r[d] = h, this.keepIntermediateTensors && (this.clonedTensorsMap[d] = this.cloneTensorList(h)), this.checkTensorForDisposal(d, c.node, r, a, o, u, l), this.processChildNodes(c.node, t, a, r, n, p));
      } else
        this.processChildNodes(c.node, t, a, r, n, p);
    }
    return m;
  }
  processChildNodes(e, t, a, r, n, o) {
    e.children.forEach((u) => {
      const [l] = B(u.name, a);
      n[l] || !o.has(u.name) || (u.op === "Merge" ? u.inputNames.some((p) => !!k(p, r, a)) && (n[l] = !0, t.push({ contexts: a.currentContext, node: u })) : u.inputNames.every((p) => !!k(p, r, a)) && (n[l] = !0, t.push({ contexts: a.currentContext, node: u })));
    });
  }
  /**
   * Releases the memory used by the weight tensors.
   */
  dispose() {
    Object.keys(this.weightMap).forEach((e) => this.weightMap[e].forEach((t) => t.dispose()));
  }
  checkInputShapeAndType(e) {
    Object.keys(e).forEach((t) => {
      const a = e[t], [r] = $(t), n = this.graph.nodes[r];
      if (n.attrParams.shape && n.attrParams.shape.value) {
        const o = n.attrParams.shape.value, u = o.length === a.shape.length && a.shape.every((l, p) => o[p] === -1 || o[p] === l);
        N(u, () => `The shape of dict['${n.name}'] provided in model.execute(dict) must be [${o}], but was [${a.shape}]`);
      }
      n.attrParams.dtype && n.attrParams.dtype.value && N(a.dtype === n.attrParams.dtype.value, () => `The dtype of dict['${n.name}'] provided in model.execute(dict) must be ${n.attrParams.dtype.value}, but was ${a.dtype}`);
    });
  }
  mapInputs(e) {
    var t, a;
    const r = {};
    for (const n in e) {
      const o = (a = (t = this._signature) === null || t === void 0 ? void 0 : t.inputs) === null || a === void 0 ? void 0 : a[n];
      o != null ? r[o.name] = e[n] : r[n] = e[n];
    }
    return r;
  }
  checkInputs(e) {
    const t = Object.keys(e).filter((a) => {
      const [r] = $(a);
      return this.graph.nodes[r] == null;
    });
    if (t.length > 0)
      throw new Error(`The dict provided in model.execute(dict) has keys: [${t}] that are not part of graph`);
  }
  mapOutputs(e) {
    return e.map((t) => {
      var a, r;
      const n = (r = (a = this._signature) === null || a === void 0 ? void 0 : a.outputs) === null || r === void 0 ? void 0 : r[t];
      return n != null ? n.name : t;
    }, {});
  }
  checkOutputs(e) {
    e.forEach((t) => {
      const [a] = $(t);
      if (!this.graph.nodes[a])
        throw new Error(`The output '${t}' is not found in the graph`);
    });
  }
}
class Xg {
  constructor(e = {}, t = {}) {
    this.hashTableNameToHandle = e, this.hashTableMap = t;
  }
  /**
   * Register a `HashTable` in the resource manager.
   *
   * The `HashTable` can be retrieved by `resourceManager.getHashTableById`,
   * where id is the table handle tensor's id.
   *
   * @param name Op node name that creates the `HashTable`.
   * @param hashTable The `HashTable` to be added to resource manager.
   */
  addHashTable(e, t) {
    this.hashTableNameToHandle[e] = t.handle, this.hashTableMap[t.id] = t;
  }
  /**
   * Get the table handle by node name.
   * @param name Op node name that creates the `HashTable`. This name is also
   *     used in the inputs list of lookup and import `HashTable` ops.
   */
  getHashTableHandleByName(e) {
    return this.hashTableNameToHandle[e];
  }
  /**
   * Get the actual `HashTable` by its handle tensor's id.
   * @param id The id of the handle tensor.
   */
  getHashTableById(e) {
    return this.hashTableMap[e];
  }
  /**
   * Dispose `ResourceManager`, including its hashTables and tensors in them.
   */
  dispose() {
    for (const e in this.hashTableMap)
      this.hashTableMap[e].clearAndClose(), delete this.hashTableMap[e];
    for (const e in this.hashTableNameToHandle)
      this.hashTableNameToHandle[e].dispose(), delete this.hashTableNameToHandle[e];
  }
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const Zg = "?tfjs-format=file", Yg = "model.json";
class Ot {
  /**
   * @param modelUrl url for the model, or an `io.IOHandler`.
   * @param weightManifestUrl url for the weight file generated by
   * scripts/convert.py script.
   * @param requestOption options for Request, which allows to send credentials
   * and custom headers.
   * @param onProgress Optional, progress callback function, fired periodically
   * before the load is completed.
   */
  constructor(e, t = {}, a = bt) {
    this.modelUrl = e, this.loadOptions = t, this.version = "n/a", this.io = a, t == null && (this.loadOptions = {}), this.resourceManager = new Xg();
  }
  // Returns the version information for the tensorflow model GraphDef.
  get modelVersion() {
    return this.version;
  }
  get inputNodes() {
    return this.executor.inputNodes;
  }
  get outputNodes() {
    return this.executor.outputNodes;
  }
  get inputs() {
    return this.executor.inputs;
  }
  get outputs() {
    return this.executor.outputs;
  }
  get weights() {
    return this.executor.weightMap;
  }
  get metadata() {
    return this.artifacts.userDefinedMetadata;
  }
  get modelSignature() {
    return this.signature;
  }
  get modelStructuredOutputKeys() {
    return this.structuredOutputKeys;
  }
  findIOHandler() {
    const e = this.modelUrl;
    if (e.load != null)
      this.handler = e;
    else if (this.loadOptions.requestInit != null)
      this.handler = this.io.browserHTTPRequest(e, this.loadOptions);
    else {
      const t = this.io.getLoadHandlers(e, this.loadOptions);
      if (t.length === 0)
        t.push(this.io.browserHTTPRequest(e, this.loadOptions));
      else if (t.length > 1)
        throw new Error(`Found more than one (${t.length}) load handlers for URL '${[e]}'`);
      this.handler = t[0];
    }
  }
  /**
   * Loads the model and weight files, construct the in memory weight map and
   * compile the inference graph.
   */
  load() {
    if (this.findIOHandler(), this.handler.load == null)
      throw new Error("Cannot proceed with model loading because the IOHandler provided does not have the `load` method implemented.");
    const e = this.handler.load();
    return Oe(e) ? e.then((t) => this.loadSync(t)) : this.loadSync(e);
  }
  /**
   * Synchronously construct the in memory weight map and
   * compile the inference graph.
   *
   * @doc {heading: 'Models', subheading: 'Classes', ignoreCI: true}
   */
  loadSync(e) {
    this.artifacts = e;
    const t = this.artifacts.modelTopology;
    let a = this.artifacts.signature;
    if (this.artifacts.userDefinedMetadata != null) {
      const n = this.artifacts.userDefinedMetadata;
      n.signature != null && (a = n.signature), n.structuredOutputKeys != null && (this.structuredOutputKeys = n.structuredOutputKeys);
    }
    this.signature = a, this.version = `${t.versions.producer}.${t.versions.minConsumer}`;
    const r = this.io.decodeWeights(this.artifacts.weightData, this.artifacts.weightSpecs);
    if (this.executor = new Ee(Vt.Instance.transformGraph(t, this.signature)), this.executor.weightMap = this.convertTensorMapToTensorsMap(r), this.executor.resourceManager = this.resourceManager, e.modelInitializer != null && e.modelInitializer.node != null) {
      const n = Vt.Instance.transformGraph(e.modelInitializer);
      this.initializer = new Ee(n), this.initializer.weightMap = this.executor.weightMap, this.initializer.resourceManager = this.resourceManager, this.initializerSignature = e.initializerSignature;
    }
    return !0;
  }
  /**
   * Save the configuration and/or weights of the GraphModel.
   *
   * An `IOHandler` is an object that has a `save` method of the proper
   * signature defined. The `save` method manages the storing or
   * transmission of serialized data ("artifacts") that represent the
   * model's topology and weights onto or via a specific medium, such as
   * file downloads, local storage, IndexedDB in the web browser and HTTP
   * requests to a server. TensorFlow.js provides `IOHandler`
   * implementations for a number of frequently used saving mediums, such as
   * `tf.io.browserDownloads` and `tf.io.browserLocalStorage`. See `tf.io`
   * for more details.
   *
   * This method also allows you to refer to certain types of `IOHandler`s
   * as URL-like string shortcuts, such as 'localstorage://' and
   * 'indexeddb://'.
   *
   * Example 1: Save `model`'s topology and weights to browser [local
   * storage](https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage);
   * then load it back.
   *
   * ```js
   * const modelUrl =
   *    'https://storage.googleapis.com/tfjs-models/savedmodel/mobilenet_v2_1.0_224/model.json';
   * const model = await tf.loadGraphModel(modelUrl);
   * const zeros = tf.zeros([1, 224, 224, 3]);
   * model.predict(zeros).print();
   *
   * const saveResults = await model.save('localstorage://my-model-1');
   *
   * const loadedModel = await tf.loadGraphModel('localstorage://my-model-1');
   * console.log('Prediction from loaded model:');
   * model.predict(zeros).print();
   * ```
   *
   * @param handlerOrURL An instance of `IOHandler` or a URL-like,
   * scheme-based string shortcut for `IOHandler`.
   * @param config Options for saving the model.
   * @returns A `Promise` of `SaveResult`, which summarizes the result of
   * the saving, such as byte sizes of the saved artifacts for the model's
   *   topology and weight values.
   *
   * @doc {heading: 'Models', subheading: 'Classes', ignoreCI: true}
   */
  async save(e, t) {
    if (typeof e == "string") {
      const a = this.io.getSaveHandlers(e);
      if (a.length === 0)
        throw new Error(`Cannot find any save handlers for URL '${e}'`);
      if (a.length > 1)
        throw new Error(`Found more than one (${a.length}) save handlers for URL '${e}'`);
      e = a[0];
    }
    if (e.save == null)
      throw new Error("GraphModel.save() cannot proceed because the IOHandler provided does not have the `save` attribute defined.");
    return e.save(this.artifacts);
  }
  addStructuredOutputNames(e) {
    if (this.structuredOutputKeys) {
      const t = e instanceof pe ? [e] : e, a = {};
      return t.forEach((r, n) => a[this.structuredOutputKeys[n]] = r), a;
    }
    return e;
  }
  /**
   * Execute the inference for the input tensors.
   *
   * @param input The input tensors, when there is single input for the model,
   * inputs param should be a `tf.Tensor`. For models with mutliple inputs,
   * inputs params should be in either `tf.Tensor`[] if the input order is
   * fixed, or otherwise NamedTensorMap format.
   *
   * For model with multiple inputs, we recommend you use NamedTensorMap as the
   * input type, if you use `tf.Tensor`[], the order of the array needs to
   * follow the
   * order of inputNodes array. @see {@link GraphModel.inputNodes}
   *
   * You can also feed any intermediate nodes using the NamedTensorMap as the
   * input type. For example, given the graph
   *    InputNode => Intermediate => OutputNode,
   * you can execute the subgraph Intermediate => OutputNode by calling
   *    model.execute('IntermediateNode' : tf.tensor(...));
   *
   * This is useful for models that uses tf.dynamic_rnn, where the intermediate
   * state needs to be fed manually.
   *
   * For batch inference execution, the tensors for each input need to be
   * concatenated together. For example with mobilenet, the required input shape
   * is [1, 244, 244, 3], which represents the [batch, height, width, channel].
   * If we are provide a batched data of 100 images, the input tensor should be
   * in the shape of [100, 244, 244, 3].
   *
   * @param config Prediction configuration for specifying the batch size.
   * Currently the batch size option is ignored for graph model.
   *
   * @returns Inference result tensors. If the model is converted and it
   * originally had structured_outputs in tensorflow, then a NamedTensorMap
   * will be returned matching the structured_outputs. If no structured_outputs
   * are present, the output will be single `tf.Tensor` if the model has single
   * output node, otherwise Tensor[].
   *
   * @doc {heading: 'Models', subheading: 'Classes'}
   */
  predict(e, t) {
    const a = this.execute(e, this.outputNodes);
    return this.addStructuredOutputNames(a);
  }
  /**
   * Execute the inference for the input tensors in async fashion, use this
   * method when your model contains control flow ops.
   *
   * @param input The input tensors, when there is single input for the model,
   * inputs param should be a `tf.Tensor`. For models with mutliple inputs,
   * inputs params should be in either `tf.Tensor`[] if the input order is
   * fixed, or otherwise NamedTensorMap format.
   *
   * For model with multiple inputs, we recommend you use NamedTensorMap as the
   * input type, if you use `tf.Tensor`[], the order of the array needs to
   * follow the
   * order of inputNodes array. @see {@link GraphModel.inputNodes}
   *
   * You can also feed any intermediate nodes using the NamedTensorMap as the
   * input type. For example, given the graph
   *    InputNode => Intermediate => OutputNode,
   * you can execute the subgraph Intermediate => OutputNode by calling
   *    model.execute('IntermediateNode' : tf.tensor(...));
   *
   * This is useful for models that uses tf.dynamic_rnn, where the intermediate
   * state needs to be fed manually.
   *
   * For batch inference execution, the tensors for each input need to be
   * concatenated together. For example with mobilenet, the required input shape
   * is [1, 244, 244, 3], which represents the [batch, height, width, channel].
   * If we are provide a batched data of 100 images, the input tensor should be
   * in the shape of [100, 244, 244, 3].
   *
   * @param config Prediction configuration for specifying the batch size.
   * Currently the batch size option is ignored for graph model.
   *
   * @returns A Promise of inference result tensors. If the model is converted
   * and it originally had structured_outputs in tensorflow, then a
   * NamedTensorMap will be returned matching the structured_outputs. If no
   * structured_outputs are present, the output will be single `tf.Tensor` if
   * the model has single output node, otherwise Tensor[].
   *
   * @doc {heading: 'Models', subheading: 'Classes'}
   */
  async predictAsync(e, t) {
    const a = await this.executeAsync(e, this.outputNodes);
    return this.addStructuredOutputNames(a);
  }
  normalizeInputs(e) {
    var t;
    if (!(e instanceof pe) && !Array.isArray(e)) {
      const n = (t = this.signature) === null || t === void 0 ? void 0 : t.inputs;
      if (n != null)
        for (const o in n) {
          const u = n[o];
          u.resourceId != null && (e[o] = this.resourceIdToCapturedInput[u.resourceId]);
        }
      return e;
    }
    e = Array.isArray(e) ? e : [e];
    const a = Object.keys(this.resourceIdToCapturedInput).length;
    if (e.length + a !== this.inputNodes.length)
      throw new Error(`Input tensor count mismatch, the graph model has ${this.inputNodes.length - a} non-resource placeholders, while there are ${e.length} input tensors provided.`);
    let r = 0;
    return this.inputNodes.reduce((n, o) => {
      var u, l, p;
      const m = (p = (l = (u = this.signature) === null || u === void 0 ? void 0 : u.inputs) === null || l === void 0 ? void 0 : l[o]) === null || p === void 0 ? void 0 : p.resourceId;
      return m != null ? n[o] = this.resourceIdToCapturedInput[m] : n[o] = e[r++], n;
    }, {});
  }
  normalizeOutputs(e) {
    return e = e || this.outputNodes, Array.isArray(e) ? e : [e];
  }
  executeInitializerGraph() {
    return this.initializer == null ? [] : this.initializerSignature == null ? this.initializer.execute({}, []) : this.initializer.execute({}, Object.keys(this.initializerSignature.outputs));
  }
  async executeInitializerGraphAsync() {
    return this.initializer == null ? [] : this.initializerSignature == null ? this.initializer.executeAsync({}, []) : this.initializer.executeAsync({}, Object.keys(this.initializerSignature.outputs));
  }
  setResourceIdToCapturedInput(e) {
    if (this.resourceIdToCapturedInput = {}, this.initializerSignature) {
      const t = this.initializerSignature.outputs, a = Object.keys(t);
      for (let r = 0; r < a.length; r++) {
        const n = a[r], o = t[n];
        this.resourceIdToCapturedInput[o.resourceId] = e[r];
      }
    }
  }
  /**
   * Executes inference for the model for given input tensors.
   * @param inputs tensor, tensor array or tensor map of the inputs for the
   * model, keyed by the input node names.
   * @param outputs output node name from the TensorFlow model, if no
   * outputs are specified, the default outputs of the model would be used.
   * You can inspect intermediate nodes of the model by adding them to the
   * outputs array.
   *
   * @returns A single tensor if provided with a single output or no outputs
   * are provided and there is only one default output, otherwise return a
   * tensor array. The order of the tensor array is the same as the outputs
   * if provided, otherwise the order of outputNodes attribute of the model.
   *
   * @doc {heading: 'Models', subheading: 'Classes'}
   */
  execute(e, t) {
    this.resourceIdToCapturedInput == null && this.setResourceIdToCapturedInput(this.executeInitializerGraph()), e = this.normalizeInputs(e), t = this.normalizeOutputs(t);
    const a = this.executor.execute(e, t);
    return a.length > 1 ? a : a[0];
  }
  /**
   * Executes inference for the model for given input tensors in async
   * fashion, use this method when your model contains control flow ops.
   * @param inputs tensor, tensor array or tensor map of the inputs for the
   * model, keyed by the input node names.
   * @param outputs output node name from the TensorFlow model, if no outputs
   * are specified, the default outputs of the model would be used. You can
   * inspect intermediate nodes of the model by adding them to the outputs
   * array.
   *
   * @returns A Promise of single tensor if provided with a single output or
   * no outputs are provided and there is only one default output, otherwise
   * return a tensor map.
   *
   * @doc {heading: 'Models', subheading: 'Classes'}
   */
  async executeAsync(e, t) {
    this.resourceIdToCapturedInput == null && this.setResourceIdToCapturedInput(await this.executeInitializerGraphAsync()), e = this.normalizeInputs(e), t = this.normalizeOutputs(t);
    const a = await this.executor.executeAsync(e, t);
    return a.length > 1 ? a : a[0];
  }
  /**
   * Get intermediate tensors for model debugging mode (flag
   * KEEP_INTERMEDIATE_TENSORS is true).
   *
   * @doc {heading: 'Models', subheading: 'Classes'}
   */
  getIntermediateTensors() {
    return this.executor.getIntermediateTensors();
  }
  /**
   * Dispose intermediate tensors for model debugging mode (flag
   * KEEP_INTERMEDIATE_TENSORS is true).
   *
   * @doc {heading: 'Models', subheading: 'Classes'}
   */
  disposeIntermediateTensors() {
    this.executor.disposeIntermediateTensors();
  }
  convertTensorMapToTensorsMap(e) {
    return Object.keys(e).reduce((t, a) => (t[a] = [e[a]], t), {});
  }
  /**
   * Releases the memory used by the weight tensors and resourceManager.
   *
   * @doc {heading: 'Models', subheading: 'Classes'}
   */
  dispose() {
    this.executor.dispose(), this.initializer && (this.initializer.dispose(), this.resourceIdToCapturedInput && Kr(this.resourceIdToCapturedInput)), this.resourceManager.dispose();
  }
}
async function Mg(s, e = {}, t = bt) {
  if (s == null)
    throw new Error("modelUrl in loadGraphModel() cannot be null. Please provide a url or an IOHandler that loads the model");
  e == null && (e = {}), e.fromTFHub && typeof s == "string" && (s = tb(s));
  const a = new Ot(s, e, t);
  return await a.load(), a;
}
function eb(s) {
  if (s == null)
    throw new Error("modelUrl in loadGraphModelSync() cannot be null. Please provide model artifacts or an IOHandler that loads the model");
  let e;
  if (s instanceof Array) {
    const [a, r] = s;
    if (!a)
      throw new Error("modelJSON must be the first element of the array");
    if (!r || !(r instanceof ArrayBuffer))
      throw new Error("An ArrayBuffer of weights must be the second element of the array");
    if (!("modelTopology" in a))
      throw new Error("Model JSON is missing 'modelTopology'");
    if (!("weightsManifest" in a))
      throw new Error("Model JSON is missing 'weightsManifest'");
    const n = cs(a.weightsManifest), o = ms(a, n, r);
    e = _e(o);
  } else if ("load" in s)
    e = s;
  else if ("modelTopology" in s && "weightSpecs" in s && "weightData" in s)
    e = _e(s);
  else
    throw new Error("Unknown model format");
  const t = new Ot(e);
  return t.load(), t;
}
function tb(s) {
  return s.endsWith("/") || (s = s + "/"), `${s}${Yg}${Zg}`;
}
/** @license See the LICENSE file. */
const Yn = "4.2.0";
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
class Mn extends ht {
  /**
   * Create a `TextLineDataset`.
   *
   * @param input A `DataSource` providing a chunked, UTF8-encoded byte stream.
   */
  constructor(e) {
    super(), this.input = e;
  }
  async iterator() {
    return (await this.input.iterator()).decodeUTF8().split(`
`).map((r) => (r.endsWith("\r") && (r = r.slice(0, -1)), r));
  }
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
const ye = '"', ne = Symbol("out"), Wt = Symbol("field"), ge = Symbol("quote"), xe = Symbol("quoteafterquote"), qt = Symbol("quoteinquote");
class ei extends ht {
  /**
   * Create a `CSVDataset`.
   *
   * @param input A `DataSource` providing a chunked, UTF8-encoded byte stream.
   * @param csvConfig (Optional) A CSVConfig object that contains configurations
   *     of reading and decoding from CSV file(s).
   *
   *     hasHeader: (Optional) A boolean value that indicates whether the first
   *     row of provided CSV file is a header line with column names, and should
   *     not be included in the data. Defaults to `true`.
   *
   *     columnNames: (Optional) A list of strings that corresponds to
   *     the CSV column names, in order. If provided, it ignores the column
   *     names inferred from the header row. If not provided, infers the column
   *     names from the first row of the records. If hasHeader is false and
   *     columnNames is not provided, this method throws an error.
   *
   *     columnConfigs: (Optional) A dictionary whose key is column names, value
   *     is an object stating if this column is required, column's data type,
   *     default value, and if this column is label. If provided, keys must
   *     correspond to names provided in columnNames or inferred from the file
   *     header lines. If isLabel is true any column, returns an array of two
   *     items: the first item is a dict of features key/value pairs, the second
   *     item is a dict of labels key/value pairs. If no feature is marked as
   *     label, returns a dict of features only.
   *
   *     configuredColumnsOnly (Optional) If true, only columns provided in
   *     columnConfigs will be parsed and provided during iteration.
   *
   *     delimiter (Optional) The string used to parse each line of the input
   *     file. Defaults to `,`.
   */
  constructor(e, t) {
    super(), this.input = e, this.hasHeader = !0, this.fullColumnNames = null, this.columnNamesValidated = !1, this.columnConfigs = null, this.configuredColumnsOnly = !1, this.delimiter = ",", this.delimWhitespace = !1, this.base = new Mn(e), t || (t = {}), this.hasHeader = t.hasHeader !== !1, this.fullColumnNames = t.columnNames, this.columnConfigs = t.columnConfigs, this.configuredColumnsOnly = t.configuredColumnsOnly, t.delimWhitespace ? (N(t.delimiter == null, () => "Delimiter should not be provided when delimWhitespace is true."), this.delimWhitespace = !0, this.delimiter = " ") : this.delimiter = t.delimiter ? t.delimiter : ",";
  }
  /**
   * Returns column names of the csv dataset. If `configuredColumnsOnly` is
   * true, return column names in `columnConfigs`. If `configuredColumnsOnly` is
   * false and `columnNames` is provided, `columnNames`. If
   * `configuredColumnsOnly` is false and `columnNames` is not provided, return
   * all column names parsed from the csv file. For example usage please go to
   * `tf.data.csv`.
   *
   * @doc {heading: 'Data', subheading: 'Classes'}
   */
  async columnNames() {
    return this.columnNamesValidated || await this.setColumnNames(), this.configuredColumnsOnly ? Object.keys(this.columnConfigs) : this.fullColumnNames;
  }
  /* 1) If `columnNames` is provided as string[], use this string[] as output
   * keys in corresponding order. The length must match the number of inferred
   * columns if `hasHeader` is true .
   * 2) If `columnNames` is not provided, parse header line as `columnNames` if
   * hasHeader is true. If `hasHeader` is false, throw an error.
   * 3) If `columnConfigs` is provided, all the keys in `columnConfigs` must
   * exist in parsed `columnNames`.
   */
  async setColumnNames() {
    const e = await this.maybeReadHeaderLine();
    if (!this.fullColumnNames && !e)
      throw new Error("Column names must be provided if there is no header line.");
    this.fullColumnNames && e && N(e.length === this.fullColumnNames.length, () => "The length of provided columnNames (" + this.fullColumnNames.length.toString() + ") does not match the length of the header line read from file (" + e.length.toString() + ")."), this.fullColumnNames || (this.fullColumnNames = e);
    const t = this.fullColumnNames.reduce((r, n) => (r[n] = r[n] + 1 || 1, r), {}), a = Object.keys(t).filter((r) => t[r] > 1);
    if (N(a.length === 0, () => "Duplicate column names found: " + a.toString()), this.columnConfigs) {
      for (const r of Object.keys(this.columnConfigs))
        if (this.fullColumnNames.indexOf(r) === -1)
          throw new Error('The key "' + r + '" provided in columnConfigs does not match any of the column names (' + this.fullColumnNames.toString() + ").");
    }
    this.columnNamesValidated = !0;
  }
  async maybeReadHeaderLine() {
    if (this.hasHeader) {
      const t = await (await this.base.iterator()).next();
      if (t.done)
        throw new Error("No data was found for CSV parsing.");
      const a = t.value;
      return this.parseRow(a, !1);
    } else
      return null;
  }
  async iterator() {
    this.columnNamesValidated || await this.setColumnNames();
    let e = await this.base.iterator();
    return this.hasHeader && (e = e.skip(1)), e.map((t) => this.makeDataElement(t));
  }
  makeDataElement(e) {
    const t = this.parseRow(e), a = {}, r = {};
    for (let n = 0; n < this.fullColumnNames.length; n++) {
      const o = this.fullColumnNames[n], u = this.columnConfigs ? this.columnConfigs[o] : null;
      if (!(this.configuredColumnsOnly && !u)) {
        const l = t[n];
        let p = null;
        if (l === "")
          if (u && u.default !== void 0)
            p = u.default;
          else {
            if (u && (u.required || u.isLabel))
              throw new Error(`Required column ${o} is empty in this line: ${e}`);
            p = void 0;
          }
        else {
          const m = Number(l);
          if (isNaN(m))
            u && u.dtype === "bool" ? p = this.getBoolean(l) : p = l;
          else if (!u || !u.dtype)
            p = m;
          else
            switch (u.dtype) {
              case "float32":
                p = m;
                break;
              case "int32":
                p = Math.floor(m);
                break;
              case "bool":
                p = this.getBoolean(l);
                break;
              default:
                p = m;
            }
        }
        u && u.isLabel ? r[o] = p : a[o] = p;
      }
    }
    return Object.keys(r).length === 0 ? a : { xs: a, ys: r };
  }
  getBoolean(e) {
    return e === "1" || e.toLowerCase() === "true" ? 1 : 0;
  }
  // adapted from https://beta.observablehq.com/@mbostock/streaming-csv
  parseRow(e, t = !0) {
    const a = [];
    let r = 0;
    const n = e.length;
    let o = ne;
    for (let u = 0; u < n; u++)
      switch (o) {
        case ne:
          switch (e.charAt(u)) {
            case ye:
              r = u + 1, o = ge;
              break;
            case this.delimiter:
              if (r = u + 1, this.delimiter === " " && this.delimWhitespace)
                break;
              a.push(""), o = ne;
              break;
            default:
              o = Wt, r = u;
              break;
          }
          break;
        case Wt:
          switch (e.charAt(u)) {
            case this.delimiter:
              a.push(e.substring(r, u)), o = ne, r = u + 1;
              break;
          }
          break;
        case ge:
          switch (e.charAt(u)) {
            case ye:
              o = xe;
              break;
          }
          break;
        case xe:
          switch (e.charAt(u)) {
            case this.delimiter:
              a.push(e.substring(r, u - 1)), o = ne, r = u + 1;
              break;
            case ye:
              o = ge;
              break;
            default:
              o = qt;
              break;
          }
          break;
        case qt:
          switch (e.charAt(u)) {
            case ye:
              o = ge;
              break;
          }
          break;
      }
    if (o === xe ? a.push(e.substring(r, n - 1)) : a.push(e.substring(r)), t && a.length !== this.fullColumnNames.length)
      throw new Error(`Invalid row in csv file. Should have ${this.fullColumnNames.length} elements in a row, but got ${a}`);
    return a;
  }
}
/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
class _t extends Ie {
  constructor(e) {
    super(), this.microphoneConfig = e, this.isClosed = !1, this.fftSize = e.fftSize || 1024;
    const t = Math.log2(this.fftSize);
    if (this.fftSize < 0 || t < 4 || t > 14 || !Number.isInteger(t))
      throw new Error(`Invalid fftSize: it must be a power of 2 between 2 to 4 and 2 to 14, but got ${this.fftSize}`);
    if (this.numFrames = e.numFramesPerSpectrogram || 43, this.sampleRateHz = e.sampleRateHz, this.columnTruncateLength = e.columnTruncateLength || this.fftSize, this.audioTrackConstraints = e.audioTrackConstraints, this.smoothingTimeConstant = e.smoothingTimeConstant || 0, this.includeSpectrogram = e.includeSpectrogram !== !1, this.includeWaveform = e.includeWaveform === !0, !this.includeSpectrogram && !this.includeWaveform)
      throw new Error("Both includeSpectrogram and includeWaveform are false. At least one type of data should be returned.");
  }
  summary() {
    return "microphone";
  }
  // Construct a MicrophoneIterator and start the audio stream.
  static async create(e = {}) {
    if (!x().get("IS_BROWSER"))
      throw new Error("microphone API is only supported in browser environment.");
    const t = new _t(e);
    return await t.start(), t;
  }
  // Start the audio stream and FFT.
  async start() {
    try {
      this.stream = await navigator.mediaDevices.getUserMedia({
        audio: this.audioTrackConstraints == null ? !0 : this.audioTrackConstraints,
        video: !1
      });
    } catch (a) {
      throw new Error(`Error thrown while initializing video stream: ${a.message}`);
    }
    if (!this.stream)
      throw new Error("Could not obtain audio from microphone.");
    const e = (
      // tslint:disable-next-line:no-any
      window.AudioContext || window.webkitAudioContext
    );
    if (this.audioContext = new e(), !this.sampleRateHz)
      this.sampleRateHz = this.audioContext.sampleRate;
    else if (this.audioContext.sampleRate !== this.sampleRateHz)
      throw new Error(`Mismatch in sampling rate: Expected: ${this.sampleRateHz}; Actual: ${this.audioContext.sampleRate}`);
    const t = this.audioContext.createMediaStreamSource(this.stream);
    this.analyser = this.audioContext.createAnalyser(), this.analyser.fftSize = this.fftSize * 2, this.analyser.smoothingTimeConstant = this.smoothingTimeConstant, t.connect(this.analyser), this.freqData = new Float32Array(this.fftSize), this.timeData = new Float32Array(this.fftSize);
  }
  async next() {
    if (this.isClosed)
      return { value: null, done: !0 };
    let e, t;
    const a = await this.getAudioData();
    if (this.includeSpectrogram) {
      const r = this.flattenQueue(a.freqDataQueue);
      e = this.getTensorFromAudioDataArray(r, [this.numFrames, this.columnTruncateLength, 1]);
    }
    if (this.includeWaveform) {
      const r = this.flattenQueue(a.timeDataQueue);
      t = this.getTensorFromAudioDataArray(r, [this.numFrames * this.fftSize, 1]);
    }
    return {
      value: { spectrogram: e, waveform: t },
      done: !1
    };
  }
  // Capture one result from the audio stream, and extract the value from
  // iterator.next() result.
  async capture() {
    return (await this.next()).value;
  }
  async getAudioData() {
    const e = [], t = [];
    let a = 0;
    return new Promise((r) => {
      const n = setInterval(() => {
        this.includeSpectrogram && (this.analyser.getFloatFrequencyData(this.freqData), this.freqData[0] === -1 / 0 && r({ freqDataQueue: e, timeDataQueue: t }), e.push(this.freqData.slice(0, this.columnTruncateLength))), this.includeWaveform && (this.analyser.getFloatTimeDomainData(this.timeData), t.push(this.timeData.slice())), ++a === this.numFrames && (clearInterval(n), r({ freqDataQueue: e, timeDataQueue: t }));
      }, this.fftSize / this.sampleRateHz * 1e3);
    });
  }
  // Stop the audio stream and pause the iterator.
  stop() {
    this.isClosed || (this.isClosed = !0, this.analyser.disconnect(), this.audioContext.close(), this.stream != null && this.stream.getTracks().length > 0 && this.stream.getTracks()[0].stop());
  }
  // Override toArray() function to prevent collecting.
  toArray() {
    throw new Error("Can not convert infinite audio stream to array.");
  }
  // Return audio sampling rate in Hz
  getSampleRate() {
    return this.sampleRateHz;
  }
  flattenQueue(e) {
    const t = e[0].length, a = new Float32Array(e.length * t);
    return e.forEach((r, n) => a.set(r, n * t)), a;
  }
  getTensorFromAudioDataArray(e, t) {
    const a = new Float32Array(le(t));
    return a.set(e, a.length - e.length), U(a, t);
  }
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
class At extends Ie {
  constructor(e, t) {
    if (super(), this.webcamVideoElement = e, this.webcamConfig = t, this.isClosed = !0, this.resize = !1, this.needToResize())
      if (this.resize = !0, this.cropSize = [this.webcamConfig.resizeHeight, this.webcamConfig.resizeWidth], this.cropBoxInd = dt([0], "int32"), this.webcamConfig.centerCrop) {
        const a = this.webcamConfig.resizeWidth * 1 / this.webcamVideoElement.width, r = this.webcamConfig.resizeHeight * 1 / this.webcamVideoElement.height, n = (1 - a) / 2, o = (1 - r) / 2, u = n + a, l = r + o;
        this.cropBox = ve([o, n, l, u], [1, 4]);
      } else
        this.cropBox = ve([0, 0, 1, 1], [1, 4]);
  }
  summary() {
    return "webcam";
  }
  // Construct a WebcamIterator and start it's video stream.
  static async create(e, t = {}) {
    if (!x().get("IS_BROWSER"))
      throw new Error("tf.data.webcam is only supported in browser environment.");
    if (!e) {
      if (e = document.createElement("video"), !t.resizeWidth || !t.resizeHeight)
        throw new Error("Please provide webcam video element, or resizeWidth and resizeHeight to create a hidden video element.");
      e.width = t.resizeWidth, e.height = t.resizeHeight;
    }
    const a = new At(e, t);
    return await a.start(), a;
  }
  // Async function to start video stream.
  async start() {
    this.webcamConfig.facingMode && N(this.webcamConfig.facingMode === "user" || this.webcamConfig.facingMode === "environment", () => `Invalid webcam facing mode: ${this.webcamConfig.facingMode}. Please provide 'user' or 'environment'`);
    try {
      this.stream = await navigator.mediaDevices.getUserMedia({
        video: {
          deviceId: this.webcamConfig.deviceId,
          facingMode: this.webcamConfig.facingMode ? this.webcamConfig.facingMode : "user",
          width: this.webcamVideoElement.width,
          height: this.webcamVideoElement.height
        }
      });
    } catch (e) {
      throw e.message = `Error thrown while initializing video stream: ${e.message}`, e;
    }
    if (!this.stream)
      throw new Error("Could not obtain video from webcam.");
    try {
      this.webcamVideoElement.srcObject = this.stream;
    } catch (e) {
      console.log(e), this.webcamVideoElement.src = window.URL.createObjectURL(this.stream);
    }
    return this.webcamVideoElement.play(), this.isClosed = !1, new Promise((e) => {
      this.webcamVideoElement.onloadedmetadata = () => {
        e();
      };
    });
  }
  async next() {
    if (this.isClosed)
      return { value: null, done: !0 };
    let e;
    try {
      e = Qu(this.webcamVideoElement);
    } catch (t) {
      throw new Error(`Error thrown converting video to pixels: ${JSON.stringify(t)}`);
    }
    if (this.resize)
      try {
        return { value: this.cropAndResizeFrame(e), done: !1 };
      } catch (t) {
        throw new Error(`Error thrown cropping the video: ${t.message}`);
      } finally {
        e.dispose();
      }
    else
      return { value: e, done: !1 };
  }
  needToResize() {
    return !!(this.webcamConfig.resizeWidth && this.webcamConfig.resizeHeight && (this.webcamVideoElement.width !== this.webcamConfig.resizeWidth || this.webcamVideoElement.height !== this.webcamConfig.resizeHeight));
  }
  // Cropping and resizing each frame based on config
  cropAndResizeFrame(e) {
    return z(() => {
      const t = mt(M(e, "float32"), 0);
      let a;
      a = ct.cropAndResize(t, this.cropBox, this.cropBoxInd, this.cropSize, "bilinear");
      const r = a.shape;
      return v(a, r.slice(1));
    });
  }
  // Capture one frame from the video stream, and extract the value from
  // iterator.next() result.
  async capture() {
    return (await this.next()).value;
  }
  // Stop the video stream and pause webcam iterator.
  stop() {
    this.stream.getTracks().forEach((t) => t.stop());
    try {
      this.webcamVideoElement.srcObject = null;
    } catch (t) {
      console.log(t), this.webcamVideoElement.src = null;
    }
    this.isClosed = !0;
  }
  // Override toArray() function to prevent collecting.
  toArray() {
    throw new Error("Can not convert infinite video stream to array.");
  }
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
class ti {
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
class si extends Ie {
  /**
   * Splits a string stream on a given separator.
   *
   * It is assumed that the incoming chunk boundaries have no semantic meaning,
   * so conceptually the incoming stream is treated simply as the concatenation
   * of its elements.
   *
   * The outgoing stream provides chunks corresponding to the results of the
   * standard string split() operation (even if such a chunk spanned incoming
   * chunks).  The separators are not included.
   *
   * A typical usage is to split a text file (represented as a stream with
   * arbitrary chunk boundaries) into lines.
   *
   * @param upstream A readable stream of strings that can be treated as
   *   concatenated.
   * @param separator A character to split on.
   */
  split(e) {
    return new sb(this, e);
  }
}
class sb extends si {
  constructor(e, t) {
    super(), this.upstream = e, this.impl = new ab(e, t);
  }
  summary() {
    return this.impl.summary();
  }
  async next() {
    return this.impl.next();
  }
}
class ab extends Jr {
  constructor(e, t) {
    super(), this.upstream = e, this.separator = t, this.carryover = "";
  }
  summary() {
    return `${this.upstream.summary()} -> Split('${this.separator}')`;
  }
  async pump() {
    const e = await this.upstream.next();
    if (e.done)
      return this.carryover === "" ? !1 : (this.outputQueue.push(this.carryover), this.carryover = "", !0);
    const t = e.value.split(this.separator);
    t[0] = this.carryover + t[0];
    for (const a of t.slice(0, -1))
      this.outputQueue.push(a);
    return this.carryover = t[t.length - 1], !0;
  }
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
class rb extends Ie {
  /**
   * Decode a stream of UTF8-encoded byte arrays to a stream of strings.
   *
   * The byte arrays producetd from the ByteChunkIterator on which this is
   * called will be interpreted as concatenated.  No assumptions are made about
   * the boundaries of the incoming chunks, so a multi-byte UTF8 encoding of a
   * character may span the boundary between chunks.  This naturally happens,
   * for instance, when reading fixed-size byte arrays from a file.
   */
  decodeUTF8() {
    return new nb(this);
  }
}
class nb extends si {
  constructor(e) {
    super(), this.upstream = e, this.impl = new ib(e);
  }
  summary() {
    return this.impl.summary();
  }
  async next() {
    return this.impl.next();
  }
}
class ib extends Jr {
  constructor(e) {
    if (super(), this.upstream = e, x().get("IS_BROWSER"))
      this.decoder = new TextDecoder("utf-8");
    else {
      const { StringDecoder: t } = require("string_decoder");
      this.decoder = new t("utf8");
    }
  }
  summary() {
    return `${this.upstream.summary()} -> Utf8`;
  }
  async pump() {
    const e = await this.upstream.next();
    let t;
    if (e.done)
      return !1;
    t = e.value;
    let a;
    return x().get("IS_BROWSER") ? a = this.decoder.decode(t, { stream: !0 }) : a = this.decoder.write(Buffer.from(t.buffer)), this.outputQueue.push(a), !0;
  }
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
class ai extends rb {
  constructor(e, t = {}) {
    super(), this.file = e, this.options = t, N(e instanceof Uint8Array || (x().get("IS_BROWSER") ? e instanceof File || e instanceof Blob : !1), () => "FileChunkIterator only supports File, Blob and Uint8Array right now."), this.offset = t.offset || 0, this.chunkSize = t.chunkSize || 1024 * 1024;
  }
  summary() {
    return `FileChunks ${this.file}`;
  }
  async next() {
    return this.offset >= (this.file instanceof Uint8Array ? this.file.byteLength : this.file.size) ? { value: null, done: !0 } : { value: await new Promise((t, a) => {
      const r = this.offset + this.chunkSize;
      if (this.file instanceof Uint8Array)
        t(new Uint8Array(this.file.slice(this.offset, r)));
      else {
        const n = new FileReader();
        n.onload = (u) => {
          let l = n.result;
          if (l instanceof ArrayBuffer && (l = new Uint8Array(l)), !(l instanceof Uint8Array))
            return a(new TypeError("FileReader returned unknown type."));
          t(l);
        }, n.onabort = (u) => a(new Error("Aborted")), n.onerror = (u) => a(new Error(u.type));
        const o = this.file.slice(this.offset, r);
        n.readAsArrayBuffer(o);
      }
      this.offset = r;
    }), done: !1 };
  }
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
async function ob(s, e = {}, t) {
  let a, r;
  typeof s == "string" ? a = s : (a = s.url, r = ub(s));
  const n = await (t || Xu)(a, r);
  if (n.ok) {
    const o = new Uint8Array(await n.arrayBuffer());
    return new ai(o, e);
  } else
    throw new Error(n.statusText);
}
const ub = (s) => ({
  method: s.method,
  headers: s.headers,
  body: s.body,
  mode: s.mode,
  credentials: s.credentials,
  cache: s.cache,
  redirect: s.redirect,
  referrer: s.referrer,
  integrity: s.integrity
});
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
function ri(s) {
  return typeof s == "string" && s.slice(0, 7) === "file://";
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
class ni extends ti {
  /**
   * Create a `FileDataSource`.
   *
   * @param input Local file path, or `File`/`Blob`/`Uint8Array` object to
   *     read. Local file only works in node environment.
   * @param options Options passed to the underlying `FileChunkIterator`s,
   *   such as {chunksize: 1024}.
   */
  constructor(e, t = {}) {
    super(), this.input = e, this.options = t;
  }
  async iterator() {
    if (ri(this.input) && x().get("IS_NODE")) {
      const e = require("fs");
      this.input = e.readFileSync(this.input.slice(7));
    }
    return new ai(this.input, this.options);
  }
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
class ii extends ti {
  /**
   * Create a `URLDataSource`.
   *
   * @param url A source URL string, or a `Request` object.
   * @param options Options passed to the underlying `FileChunkIterator`s,
   *   such as {chunksize: 1024}.
   */
  constructor(e, t = {}) {
    super(), this.url = e, this.fileOptions = t;
  }
  // TODO(soergel): provide appropriate caching options.  Currently this
  // will download the URL anew for each call to iterator().  Since we have
  // to treat the downloaded file as a blob/buffer anyway, we may as well retain
  // it-- but that raises GC issues.  Also we may want a persistent disk cache.
  async iterator() {
    return ri(this.url) ? new ni(this.url, this.fileOptions).iterator() : ob(this.url, this.fileOptions);
  }
}
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * =============================================================================
 */
function lb(s, e = {}) {
  return new ei(new ii(s), e);
}
function pb(s) {
  const e = Qr(s);
  return Xr(async () => e);
}
function mb(s) {
  return Xr(async () => {
    const e = await s();
    return Qr(() => e.next());
  });
}
async function cb(s, e) {
  return At.create(s, e);
}
async function db(s) {
  return _t.create(s);
}
/** @license See the LICENSE file. */
const oi = "4.2.0";
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const hb = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  CSVDataset: ei,
  Dataset: ht,
  FileDataSource: ni,
  TextLineDataset: Mn,
  URLDataSource: ii,
  array: Zu,
  csv: lb,
  func: pb,
  generator: mb,
  microphone: db,
  version_data: oi,
  webcam: cb,
  zip: Yu
}, Symbol.toStringTag, { value: "Module" }));
/** @license See the LICENSE file. */
const fb = "4.2.0";
/** @license See the LICENSE file. */
const yb = "4.2.0";
/** @license See the LICENSE file. */
const gb = "4.2.0";
/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
const bb = {
  "tfjs-core": jn,
  "tfjs-backend-cpu": fb,
  "tfjs-backend-webgl": yb,
  "tfjs-data": oi,
  "tfjs-layers": Zr,
  "tfjs-converter": Yn,
  tfjs: gb
}, Nb = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  Abs: Mu,
  Acos: el,
  Acosh: tl,
  AdadeltaOptimizer: sl,
  AdagradOptimizer: al,
  AdamOptimizer: rl,
  AdamaxOptimizer: nl,
  Add: il,
  AddN: Gt,
  All: ol,
  Any: ul,
  ArgMax: ll,
  ArgMin: pl,
  Asin: ml,
  Asinh: cl,
  Atan: dl,
  Atan2: hl,
  Atanh: fl,
  AvgPool: yl,
  AvgPool3D: gl,
  AvgPool3DGrad: bl,
  AvgPoolGrad: Nl,
  BatchMatMul: wl,
  BatchToSpaceND: Tl,
  Bincount: Sl,
  BroadcastArgs: Kt,
  BroadcastTo: vl,
  Callback: Gn,
  CallbackList: Ol,
  Cast: _l,
  Ceil: Al,
  ClipByValue: El,
  Complex: kl,
  ComplexAbs: Il,
  Concat: Dl,
  Conv2D: $l,
  Conv2DBackpropFilter: Cl,
  Conv2DBackpropInput: zl,
  Conv3D: xl,
  Conv3DBackpropFilterV2: Ll,
  Conv3DBackpropInputV2: Vl,
  Cos: Fl,
  Cosh: Pl,
  CropAndResize: Rl,
  Cumprod: jl,
  Cumsum: Bl,
  CustomCallback: Hl,
  DataStorage: Wl,
  DenseBincount: ql,
  DepthToSpace: Ul,
  DepthwiseConv2dNative: Gl,
  DepthwiseConv2dNativeBackpropFilter: Kl,
  DepthwiseConv2dNativeBackpropInput: Jl,
  Diag: Jt,
  Dilation2D: Ql,
  Dilation2DBackpropFilter: Xl,
  Dilation2DBackpropInput: Zl,
  get ENV() {
    return Yl;
  },
  EarlyStopping: Kn,
  Einsum: Qt,
  Elu: Ml,
  EluGrad: ep,
  Environment: tp,
  Equal: sp,
  Erf: ap,
  Exp: rp,
  ExpandDims: np,
  Expm1: ip,
  FFT: op,
  Fill: up,
  FlipLeftRight: lp,
  Floor: pp,
  FloorDiv: mp,
  FromPixels: cp,
  FusedBatchNorm: dp,
  FusedConv2D: hp,
  FusedDepthwiseConv2D: Fe,
  GatherNd: os,
  GatherV2: fp,
  GraphModel: Ot,
  Greater: yp,
  GreaterEqual: gp,
  History: bp,
  IFFT: Np,
  Identity: wp,
  Imag: Tp,
  InputSpec: Sp,
  IsFinite: vp,
  IsInf: Op,
  IsNan: _p,
  KernelBackend: Ap,
  LRN: Ep,
  LRNGrad: kp,
  LayerVariable: Ip,
  LayersModel: it,
  LeakyRelu: Dp,
  Less: $p,
  LessEqual: Cp,
  LinSpace: Xt,
  Log: zp,
  Log1p: xp,
  LogSoftmax: Lp,
  LogicalAnd: Vp,
  LogicalNot: Fp,
  LogicalOr: Pp,
  LogicalXor: Rp,
  LowerBound: jp,
  Max: Bp,
  MaxPool: Hp,
  MaxPool3D: Wp,
  MaxPool3DGrad: qp,
  MaxPoolGrad: Up,
  MaxPoolWithArgmax: Yt,
  Maximum: Gp,
  Mean: Kp,
  Min: Jp,
  Minimum: Qp,
  MirrorPad: Xp,
  Mod: Zp,
  MomentumOptimizer: Yp,
  Multinomial: Mt,
  Multiply: Mp,
  Neg: em,
  NonMaxSuppressionV3: tm,
  NonMaxSuppressionV4: sm,
  NonMaxSuppressionV5: am,
  NotEqual: rm,
  OP_SCOPE_SUFFIX: hs,
  OneHot: nm,
  OnesLike: im,
  Optimizer: om,
  OptimizerConstructors: um,
  Pack: lm,
  PadV2: pm,
  Pool: mm,
  Pow: cm,
  Prelu: dm,
  Prod: hm,
  RMSPropOptimizer: fm,
  RNN: ot,
  RaggedGather: es,
  RaggedRange: ts,
  RaggedTensorToTensor: ss,
  Range: ym,
  get Rank() {
    return gm;
  },
  Real: bm,
  RealDiv: Nm,
  Reciprocal: wm,
  get Reduction() {
    return Tm;
  },
  Relu: Sm,
  Relu6: vm,
  Reshape: Om,
  ResizeBilinear: _m,
  ResizeBilinearGrad: Am,
  ResizeNearestNeighbor: Em,
  ResizeNearestNeighborGrad: km,
  Reverse: Im,
  RotateWithOffset: Dm,
  Round: $m,
  Rsqrt: Cm,
  SGDOptimizer: zm,
  ScatterNd: ns,
  SearchSorted: Zt,
  Select: xm,
  Selu: Lm,
  Sequential: ds,
  Sigmoid: Vm,
  Sign: Fm,
  Sin: Pm,
  Sinh: Rm,
  Slice: jm,
  Softmax: Bm,
  Softplus: Hm,
  SpaceToBatchND: Wm,
  SparseFillEmptyRows: qm,
  SparseReshape: Um,
  SparseSegmentMean: Gm,
  SparseSegmentSum: Km,
  SparseToDense: is,
  SplitV: Jm,
  Sqrt: Qm,
  Square: Xm,
  SquaredDifference: Zm,
  Step: Ym,
  StridedSlice: Mm,
  StringNGrams: ec,
  StringSplit: tc,
  StringToHashBucketFast: sc,
  Sub: ac,
  Sum: rc,
  SymbolicTensor: nc,
  Tan: ic,
  Tanh: oc,
  Tensor: pe,
  TensorBuffer: Le,
  Tile: uc,
  TopK: lc,
  Transform: pc,
  Transpose: mc,
  Unique: cc,
  Unpack: dc,
  UnsortedSegmentSum: hc,
  UpperBound: fc,
  Variable: yc,
  ZerosLike: gc,
  _FusedMatMul: bc,
  abs: fs,
  acos: ys,
  acosh: gs,
  add: G,
  addN: Yr,
  all: bs,
  any: Ns,
  argMax: ws,
  argMin: Ts,
  asin: Ss,
  asinh: vs,
  atan: Os,
  atan2: _s,
  atanh: As,
  avgPool: Es,
  avgPool3d: ks,
  backend: Nc,
  backend_util: wc,
  basicLSTMCell: Mr,
  batchNorm: Is,
  batchNorm2d: Ds,
  batchNorm3d: $s,
  batchNorm4d: Cs,
  batchToSpaceND: zs,
  bincount: xs,
  booleanMaskAsync: Cn,
  broadcastArgs: en,
  broadcastTo: Ls,
  broadcast_util: Tc,
  browser: Sc,
  buffer: Xe,
  callbacks: ky,
  cast: M,
  ceil: Vs,
  clipByValue: Fs,
  clone: pt,
  complex: Ps,
  concat: ce,
  concat1d: Rs,
  concat2d: js,
  concat3d: Bs,
  concat4d: Hs,
  constraints: Oh,
  conv1d: Ws,
  conv2d: qs,
  conv2dTranspose: Us,
  conv3d: Gs,
  conv3dTranspose: Ks,
  copyRegisteredKernels: vc,
  cos: Js,
  cosh: Qs,
  cosineWindow: Xs,
  cumprod: Zs,
  cumsum: Ys,
  customGrad: Ve,
  data: hb,
  denseBincount: Ms,
  deprecationWarn: Oc,
  depthToSpace: ea,
  depthwiseConv2d: rt,
  deregisterOp: Dy,
  device_util: _c,
  diag: tn,
  dilation2d: ta,
  disableDeprecationWarnings: Ac,
  dispose: Kr,
  disposeVariables: Ec,
  div: st,
  divNoNan: sa,
  dot: aa,
  dropout: ra,
  einsum: sn,
  elu: na,
  enableDebugMode: kc,
  enableProdMode: Ic,
  enclosingPowerOfTwo: ia,
  engine: Dc,
  env: x,
  equal: oa,
  erf: ua,
  euclideanNorm: la,
  exp: pa,
  expandDims: mt,
  expm1: ma,
  eye: ca,
  fft: da,
  fill: ha,
  findBackend: $c,
  findBackendFactory: Cc,
  floor: fa,
  floorDiv: ya,
  fused: Pn,
  gather: tt,
  gatherND: Vn,
  gather_util: zc,
  getBackend: xc,
  getGradient: Lc,
  getKernel: Vc,
  getKernelsForBackend: Fc,
  grad: Pc,
  grads: Rc,
  greater: ga,
  greaterEqual: ba,
  ifft: Na,
  imag: wa,
  image: ct,
  inTopKAsync: Fn,
  initializers: jh,
  input: Bn,
  io: bt,
  irfft: Ta,
  isFinite: Sa,
  isInf: va,
  isNaN: Oa,
  keep: P,
  kernel_impls: Nh,
  layers: ny,
  leakyRelu: _a,
  less: Aa,
  lessEqual: Ea,
  linalg: ka,
  linspace: an,
  loadGraphModel: Mg,
  loadGraphModelSync: eb,
  loadLayersModel: jc,
  localResponseNormalization: Ia,
  log: Da,
  log1p: $a,
  logSigmoid: Ca,
  logSoftmax: za,
  logSumExp: xa,
  logicalAnd: La,
  logicalNot: Va,
  logicalOr: Fa,
  logicalXor: Pa,
  losses: Ra,
  lowerBound: rn,
  matMul: W,
  math: bh,
  max: ja,
  maxPool: Ba,
  maxPool3d: Ha,
  maxPoolWithArgmax: nn,
  maximum: Wa,
  mean: qa,
  memory: Bc,
  meshgrid: on,
  metrics: Ty,
  min: Ua,
  minimum: Ga,
  mirrorPad: Ka,
  mod: Ja,
  model: Bh,
  models: Sy,
  moments: Qa,
  movingAverage: zn,
  mul: Y,
  multiRNNCell: un,
  multinomial: ln,
  neg: Xa,
  nextFrame: Hc,
  norm: Za,
  notEqual: Ya,
  oneHot: Se,
  ones: Z,
  onesLike: Ma,
  op: S,
  outerProduct: pn,
  pad: te,
  pad1d: mn,
  pad2d: cn,
  pad3d: dn,
  pad4d: hn,
  pool: er,
  pow: at,
  prelu: tr,
  print: sr,
  prod: ar,
  profile: Wc,
  raggedGather: fn,
  raggedRange: yn,
  raggedTensorToTensor: gn,
  rand: bn,
  randomGamma: Tn,
  randomNormal: Ze,
  randomStandardNormal: Sn,
  randomUniform: rr,
  range: nr,
  ready: qc,
  real: ir,
  reciprocal: or,
  registerBackend: Uc,
  registerCallbackConstructor: Wh,
  registerGradient: Gc,
  registerKernel: Kc,
  registerOp: Iy,
  regularizers: Ay,
  relu: ur,
  relu6: lr,
  removeBackend: Jc,
  reshape: v,
  reverse: se,
  reverse1d: vn,
  reverse2d: On,
  reverse3d: _n,
  reverse4d: An,
  rfft: pr,
  round: mr,
  rsqrt: cr,
  scalar: R,
  scatterND: xn,
  scatter_util: Qc,
  searchSorted: De,
  selu: dr,
  separableConv2d: hr,
  sequential: Hh,
  serialization: Xc,
  setBackend: Zc,
  setPlatform: Yc,
  setdiff1dAsync: En,
  sigmoid: ie,
  sign: fr,
  signal: yr,
  sin: gr,
  sinh: br,
  slice: q,
  slice1d: Nr,
  slice2d: wr,
  slice3d: Tr,
  slice4d: Sr,
  slice_util: Mc,
  softmax: vr,
  softplus: Or,
  spaceToBatchND: _r,
  sparse: Ar,
  sparseToDense: Ln,
  spectral: Er,
  split: kr,
  sqrt: Ir,
  square: Dr,
  squaredDifference: $r,
  squeeze: et,
  stack: ee,
  step: Cr,
  stridedSlice: zr,
  string: xr,
  sub: oe,
  sum: Lr,
  sumOutType: ed,
  tan: Vr,
  tanh: Te,
  tensor: U,
  tensor1d: dt,
  tensor2d: ve,
  tensor3d: Fr,
  tensor4d: kn,
  tensor5d: In,
  tensor6d: Dn,
  tensor_util: td,
  test_util: Bd,
  tidy: z,
  tile: Pr,
  time: sd,
  topk: Rr,
  train: ad,
  transpose: nt,
  truncatedNormal: jr,
  unique: Br,
  unregisterGradient: rd,
  unregisterKernel: nd,
  unsortedSegmentSum: Hr,
  unstack: ae,
  upcastType: id,
  upperBound: $n,
  util: od,
  valueAndGrad: ud,
  valueAndGrads: ld,
  variable: Wr,
  variableGrads: pd,
  version: bb,
  version_converter: Yn,
  version_core: jn,
  version_layers: Zr,
  where: qr,
  whereAsync: gt,
  zeros: Ur,
  zerosLike: Gr
}, Symbol.toStringTag, { value: "Module" })), ui = new Ne();
ui.compose(new we(), new Ut(), new we(1e-3, 1e-3, 1e-3));
const wb = new Ne().set(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);
class Tb {
  constructor({
    container: e,
    imageTargetSrc: t,
    maxTrack: a,
    uiLoading: r = "yes",
    uiScanning: n = "yes",
    uiError: o = "yes",
    filterMinCF: u = null,
    filterBeta: l = null,
    warmupTolerance: p = null,
    missTolerance: m = null,
    userDeviceId: c = null,
    environmentDeviceId: d = null
  }) {
    this.container = e, this.imageTargetSrc = t, this.maxTrack = a, this.filterMinCF = u, this.filterBeta = l, this.warmupTolerance = p, this.missTolerance = m, this.ui = new dd({ uiLoading: r, uiScanning: n, uiError: o }), this.userDeviceId = c, this.environmentDeviceId = d, this.shouldFaceUser = !1, this.scene = new It(), this.cssScene = new It(), this.renderer = new pi({ antialias: !0, alpha: !0 }), this.cssRenderer = new cd({ antialias: !0 }), this.renderer.outputEncoding = mi, this.renderer.setPixelRatio(window.devicePixelRatio), this.camera = new ci(), this.anchors = [], this.renderer.domElement.style.position = "absolute", this.cssRenderer.domElement.style.position = "absolute", this.container.appendChild(this.renderer.domElement), this.container.appendChild(this.cssRenderer.domElement), window.addEventListener("resize", this.resize.bind(this));
  }
  async start() {
    this.ui.showLoading(), await this._startVideo(), await this._startAR();
  }
  stop() {
    this.controller.stopProcessVideo(), this.video.srcObject.getTracks().forEach(function(t) {
      t.stop();
    }), this.video.remove();
  }
  switchCamera() {
    this.shouldFaceUser = !this.shouldFaceUser, this.stop(), this.start();
  }
  addAnchor(e) {
    const t = new Dt();
    t.visible = !1, t.matrixAutoUpdate = !1;
    const a = { group: t, targetIndex: e, onTargetFound: null, onTargetLost: null, onTargetUpdate: null, css: !1, visible: !1 };
    return this.anchors.push(a), this.scene.add(t), a;
  }
  addCSSAnchor(e) {
    const t = new Dt();
    t.visible = !1, t.matrixAutoUpdate = !1;
    const a = { group: t, targetIndex: e, onTargetFound: null, onTargetLost: null, onTargetUpdate: null, css: !0, visible: !1 };
    return this.anchors.push(a), this.cssScene.add(t), a;
  }
  _startVideo() {
    return new Promise((e, t) => {
      if (this.video = document.createElement("video"), this.video.setAttribute("autoplay", ""), this.video.setAttribute("muted", ""), this.video.setAttribute("playsinline", ""), this.video.style.position = "absolute", this.video.style.top = "0px", this.video.style.left = "0px", this.video.style.zIndex = "-2", this.container.appendChild(this.video), !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        this.ui.showCompatibility(), t();
        return;
      }
      const a = {
        audio: !1,
        video: {}
      };
      this.shouldFaceUser ? this.userDeviceId ? a.video.deviceId = { exact: this.userDeviceId } : a.video.facingMode = "user" : this.environmentDeviceId ? a.video.deviceId = { exact: this.environmentDeviceId } : a.video.facingMode = "environment", navigator.mediaDevices.getUserMedia(a).then((r) => {
        this.video.addEventListener("loadedmetadata", () => {
          this.video.setAttribute("width", this.video.videoWidth), this.video.setAttribute("height", this.video.videoHeight), e();
        }), this.video.srcObject = r;
      }).catch((r) => {
        console.log("getUserMedia error", r), t();
      });
    });
  }
  _startAR() {
    return new Promise(async (e, t) => {
      const a = this.video;
      this.container, this.controller = new md({
        inputWidth: a.videoWidth,
        inputHeight: a.videoHeight,
        filterMinCF: this.filterMinCF,
        filterBeta: this.filterBeta,
        warmupTolerance: this.warmupTolerance,
        missTolerance: this.missTolerance,
        maxTrack: this.maxTrack,
        onUpdate: (n) => {
          if (n.type === "updateMatrix") {
            const { targetIndex: o, worldMatrix: u } = n;
            for (let p = 0; p < this.anchors.length; p++)
              if (this.anchors[p].targetIndex === o) {
                if (this.anchors[p].css ? this.anchors[p].group.children.forEach((m) => {
                  m.element.style.visibility = u === null ? "hidden" : "visible";
                }) : this.anchors[p].group.visible = u !== null, u !== null) {
                  let m = new Ne();
                  m.elements = [...u], m.multiply(this.postMatrixs[o]), this.anchors[p].css && m.multiply(ui), this.anchors[p].group.matrix = m;
                } else
                  this.anchors[p].group.matrix = wb;
                this.anchors[p].visible && u === null && (this.anchors[p].visible = !1, this.anchors[p].onTargetLost && this.anchors[p].onTargetLost()), !this.anchors[p].visible && u !== null && (this.anchors[p].visible = !0, this.anchors[p].onTargetFound && this.anchors[p].onTargetFound()), this.anchors[p].onTargetUpdate && this.anchors[p].onTargetUpdate();
              }
            this.anchors.reduce((p, m) => p || m.visible, !1) ? this.ui.hideScanning() : this.ui.showScanning();
          }
        }
      }), this.resize();
      const { dimensions: r } = await this.controller.addImageTargets(this.imageTargetSrc);
      this.postMatrixs = [];
      for (let n = 0; n < r.length; n++) {
        const o = new we(), u = new Ut(), l = new we(), [p, m] = r[n];
        o.x = p / 2, o.y = p / 2 + (m - p) / 2, l.x = p, l.y = p, l.z = p;
        const c = new Ne();
        c.compose(o, u, l), this.postMatrixs.push(c);
      }
      await this.controller.dummyRun(this.video), this.ui.hideLoading(), this.ui.showScanning(), this.controller.processVideo(this.video), e();
    });
  }
  resize() {
    const { renderer: e, cssRenderer: t, camera: a, container: r, video: n } = this;
    if (!n)
      return;
    this.video.setAttribute("width", this.video.videoWidth), this.video.setAttribute("height", this.video.videoHeight);
    let o, u;
    const l = n.videoWidth / n.videoHeight, p = r.clientWidth / r.clientHeight;
    l > p ? (u = r.clientHeight, o = u * l) : (o = r.clientWidth, u = o / l);
    const m = this.controller.getProjectionMatrix(), c = this.controller.inputWidth / this.controller.inputHeight;
    let d;
    c > p ? d = this.video.width / this.controller.inputWidth : d = this.video.height / this.controller.inputHeight;
    let h, b;
    c > p ? (h = r.clientHeight, h *= d) : (b = r.clientWidth, h = b / this.controller.inputWidth * this.controller.inputHeight, h *= d);
    let f = r.clientHeight / h;
    const y = 2 * Math.atan(1 / m[5] * f) * 180 / Math.PI, T = m[14] / (m[10] - 1), _ = m[14] / (m[10] + 1);
    m[5] / m[0], a.fov = y, a.near = T, a.far = _, a.aspect = r.clientWidth / r.clientHeight, a.updateProjectionMatrix(), n.style.top = -(u - r.clientHeight) / 2 + "px", n.style.left = -(o - r.clientWidth) / 2 + "px", n.style.width = o + "px", n.style.height = u + "px";
    const w = e.domElement, I = t.domElement;
    w.style.position = "absolute", w.style.left = 0, w.style.top = 0, w.style.width = r.clientWidth + "px", w.style.height = r.clientHeight + "px", I.style.position = "absolute", I.style.left = 0, I.style.top = 0, I.style.width = r.clientWidth + "px", I.style.height = r.clientHeight + "px", e.setSize(r.clientWidth, r.clientHeight), t.setSize(r.clientWidth, r.clientHeight);
  }
}
window.MINDAR || (window.MINDAR = {});
window.MINDAR.IMAGE || (window.MINDAR.IMAGE = {});
window.MINDAR.IMAGE.MindARThree = Tb;
window.MINDAR.IMAGE.tf = Nb;
export {
  Tb as MindARThree
};
