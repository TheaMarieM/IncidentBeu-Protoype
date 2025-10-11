import React from "react";
import styles from "../portfolio.module.css";

export default function Hero() {
  return (
    <section className={styles.hero}>
      <h2>Hi, I'm [Your Name]</h2>
      <p>
        I'm a developer who builds web apps with a focus on clean design and
        performance. I enjoy building useful tools and learning new
        technologies.
      </p>
    </section>
  );
}
