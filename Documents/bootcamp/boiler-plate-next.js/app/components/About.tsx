import React from "react";
import styles from "../portfolio.module.css";

export default function About() {
  return (
    <section className={styles.about}>
      <h3>About me</h3>
      <p>
        I build web applications and enjoy working across the stack. I care
        about accessibility, performance, and maintainable code.
      </p>
    </section>
  );
}
